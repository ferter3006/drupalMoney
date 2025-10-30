<?php

declare(strict_types=1);

namespace Drupal\moneylink_auth;

use Drupal\moneylink_store\MoneyLinkStoreService;
use Drupal\user\Entity\User;
use GuzzleHttp\Client;

/**
 * Servicio de autenticación para Moneylink.
 */
final class MoneyLinkAuthService
{

  public function __construct(
    private Client $httpClient,
    private MoneyLinkStoreService $storeService,
  ) {}

  /**
   * Enviamos petición de login a la API.
   * @return array
   * @param string $email
   * @param string $password
   * @Autor Lluís Ferrater
   */
  public function login(string $email, string $password): array
  {
    $response = $this->httpClient->post('https://ioc.ferter.es/api/users/login', [
      'headers' => [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
      ],
      'json' => [
        'email' => $email,
        'password' => $password,
      ],
    ]);

    // Parseamos la respuesta JSON
    $data = json_decode($response->getBody()->getContents(), true);

    if (!empty($data['status']) && $data['status'] == "1") {
      // Guardar datos usando el servicio de store
      $this->storeService->setUserData($data['user']);
      $this->storeService->setAuthToken($data['token']);
      
      // Sincronizar usuario con Drupal y loguear
      $this->syncAndLoginDrupalUser($data['user'], $data['token']);
    }

    return $data;
  }

  /**
   * Enviamos petición de logout a la API.
   * @return array
   * @Autor Lluís Ferrater
   */

  public function logout(): array
  {
    $token = $this->storeService->getAuthToken();
    
    // Cerrar sesión de Drupal
    if (function_exists('user_logout')) {
      user_logout();
    }
    
    // Borramos datos del usuario del store
    $this->storeService->clearUserData();

    try {
      $response = $this->httpClient->post('https://ioc.ferter.es/api/users/logout', [
        'headers' => [
          'Content-Type' => 'application/json',
          'Accept' => 'application/json',
          'Authorization' => 'Bearer ' . $token,
        ],
      ]);

      $data = json_decode($response->getBody()->getContents(), true);


      // Devolvemos los datos de la respuesta
      return $data;
    } catch (\GuzzleHttp\Exception\ClientException $e) {
      // Si la API devuelve un error controlado, capturamos el mensaje
      $response = $e->getResponse();
      $body = $response ? $response->getBody()->getContents() : '';
      $data = json_decode($body, TRUE);
      $api_message = $data['message'] ?? 'Unknown API error during logout.';

      // Devolvemos un mensaje de error personalizado
      return ['status' => 0, 'message' => 'Logout failed: ' . $api_message];
    } catch (\Exception $e) {
      // Si ocurre un error inesperado, devolvemos un mensaje genérico
      return ['status' => 0, 'message' => 'An unexpected error occurred during logout: ' . $e->getMessage()];
    }
  }

  /**
   * Enviamos petición de actualización de datos del usuario a la API.
   *
   * @param array $updateData
   *   Datos a actualizar. Puede contener 'name', 'email', 'password'.
   * @return array
   *   La respuesta de la API.
   * @Autor Lluís Ferrater
   */
  public function updateUser(array $updateData): array
  {
    // Obtenemos el token de autenticación
    $token = $this->storeService->getAuthToken();

    // Si no hay token, el usuario no está autenticado.
    if (!$token) {
      return ['status' => 0, 'message' => 'User not authenticated.'];
    }

    try {
      // Realizamos la petición POST a la API para actualizar los datos.
      $response = $this->httpClient->post('https://ioc.ferter.es/api/users/me', [
        'headers' => [
          'Content-Type' => 'application/json',
          'Accept' => 'application/json',
          // Incluimos el token de autorización para identificar al usuario.
          'Authorization' => 'Bearer ' . $token,
        ],
        'json' => $updateData,
      ]);

      $data = json_decode($response->getBody()->getContents(), true);

      // Si la actualización es exitosa, actualizamos los datos del usuario
      if (!empty($data['status']) && $data['status'] == "1") {
        $this->storeService->setUserData($data['user']);
      }

      return $data;
    } catch (\GuzzleHttp\Exception\ClientException $e) {
      // Capturamos errores controlados de la API (ej. 4xx).
      $response = $e->getResponse();
      $body = $response ? $response->getBody()->getContents() : '';
      $data = json_decode($body, TRUE);
      return $data ?? ['status' => 0, 'message' => 'An API error occurred during user update.'];
    } catch (\Exception $e) {
      // Capturamos cualquier otro error inesperado.
      return ['status' => 0, 'message' => 'An unexpected error occurred: ' . $e->getMessage()];
    }
  }

  /**
   * Sincroniza usuario de API con Drupal y loguea al usuario.
   * 
   * @param array $userData
   *   Datos del usuario desde la API.
   * @param string $token
   *   Token de autenticación de la API.
   */
  private function syncAndLoginDrupalUser(array $userData, string $token): void
  {
    $email = $userData['email'] ?? null;
    $name = $userData['name'] ?? null;
    $external_id = $userData['id'] ?? null;

    if (!$email || !$external_id) {
      // Sin email o ID no podemos sincronizar
      return;
    }

    // Buscar usuario por email
    $users = \Drupal::entityTypeManager()
      ->getStorage('user')
      ->loadByProperties(['mail' => $email]);

    if (empty($users)) {
      // Usuario no existe en Drupal - crear nuevo
      try {
        $user = User::create([
          'name' => $email, // Username = email
          'mail' => $email,
          'status' => 1, // Activo
          'init' => $email,
        ]);
        
        // Guardar sin validar contraseña (no tiene contraseña en Drupal)
        $user->enforceIsNew();
        $user->save();
        
        \Drupal::logger('moneylink_auth')->notice('Created Drupal user for @email (external ID: @id)', [
          '@email' => $email,
          '@id' => $external_id,
        ]);
      } catch (\Exception $e) {
        \Drupal::logger('moneylink_auth')->error('Failed to create Drupal user: @message', [
          '@message' => $e->getMessage(),
        ]);
        return;
      }
    } else {
      // Usuario ya existe
      $user = reset($users);
    }

    // Loguear usuario en Drupal (sesión nativa)
    // user_login_finalize() es una función global de Drupal
    if (function_exists('user_login_finalize')) {
      user_login_finalize($user);
    }
    
    \Drupal::logger('moneylink_auth')->info('User @email logged in via API authentication', [
      '@email' => $email,
    ]);
  }
}
