<?php

declare(strict_types=1);

namespace Drupal\moneylink_apisalas;

use Drupal\moneylink_store\MoneyLinkStoreService;
use GuzzleHttp\Client;

/**
 * @todo Add class description.
 */
final class MoneyLinkApiSalasService
{

  // ruta global de la API
  private const API_BASE_URL = 'https://ioc.ferter.es/api/salas';

  public function __construct(
    private Client $httpClient,
    private MoneyLinkStoreService $storeService,
  ) {}

  /**
   * Obtiene todas las salas del usuario autenticado.
   * 
   * @return array
   *   Lista de salas del usuario o error.
   */
  public function getMySalas(): array
  {
    $token = $this->storeService->getAuthToken();

    if (!$token) {
      return ['status' => 0, 'message' => 'User not authenticated.'];
    }

    // Verificar si el token ha expirado localmente
    if (!$this->storeService->isTokenValid()) {
      $this->storeService->clearUserData();
      \Drupal::service('session_manager')->destroy();
      
      return [
        'status' => 0, 
        'message' => 'Authentication expired. Please log in again.',
        'redirect_to_logout' => true
      ];
    }

    try {
      $response = $this->httpClient->get(self::API_BASE_URL . '/me', [
        'headers' => [
          'Content-Type' => 'application/json',
          'Accept' => 'application/json',
          'Authorization' => 'Bearer ' . $token,
        ],
      ]);

      $data = json_decode($response->getBody()->getContents(), true);

      return $data;
    } catch (\GuzzleHttp\Exception\ClientException $e) {
      // Si es un error 401, limpiar token inválido
      if ($e->getResponse() && $e->getResponse()->getStatusCode() === 401) {
        $this->storeService->clearUserData();
        \Drupal::service('session_manager')->destroy();
        
        \Drupal::logger('moneylink_auth')->warning('API returned 401 during getMySalas for user @uid', [
          '@uid' => \Drupal::currentUser()->id(),
        ]);
        
        return [
          'status' => 0, 
          'message' => 'Authentication expired. Please log in again.',
          'redirect_to_logout' => true
        ];
      }
      
      \Drupal::logger('moneylink_auth')->error('API error during getMySalas: @message', [
        '@message' => $e->getMessage(),
      ]);
      
      return [
        'status' => 0,
        'message' => 'Error fetching user salas: ' . $e->getMessage(),
      ];
    } catch (\Exception $e) {
      \Drupal::logger('moneylink_auth')->error('Unexpected error during getMySalas: @message', [
        '@message' => $e->getMessage(),
      ]);
      
      return [
        'status' => 0,
        'message' => 'Error fetching user salas: ' . $e->getMessage(),
      ];
    }
  }

  /**
   * Get info de una sala por su ID.
   */

  public function showSalaById(string $salaId, string $mes): array
  {
    $token = $this->storeService->getAuthToken();

    if (!$token) {
      return ['status' => 0, 'message' => 'User not authenticated.'];
    }

    try {
      $response = $this->httpClient->get(self::API_BASE_URL . '/' . $salaId . '/' . $mes, [
        'headers' => [
          'Content-Type' => 'application/json',
          'Accept' => 'application/json',
          'Authorization' => 'Bearer ' . $token,
        ],
      ]);

      $data = json_decode($response->getBody()->getContents(), true);

      return $data;
    } catch (\GuzzleHttp\Exception\ClientException $e) {
      // Si es un error 401, limpiar token inválido
      if ($e->getResponse() && $e->getResponse()->getStatusCode() === 401) {
        $this->storeService->clearUserData();
        \Drupal::service('session_manager')->destroy();
        
        \Drupal::logger('moneylink_auth')->warning('API returned 401 during getSalaInfo for user @uid', [
          '@uid' => \Drupal::currentUser()->id(),
        ]);
        
        return [
          'status' => 0, 
          'message' => 'Authentication expired. Please log in again.',
          'redirect_to_logout' => true
        ];
      }
      
      \Drupal::logger('moneylink_auth')->error('API error during getSalaInfo: @message', [
        '@message' => $e->getMessage(),
      ]);
      
      return [
        'status' => 0,
        'message' => 'Error fetching sala info: ' . $e->getMessage(),
      ];
    } catch (\Exception $e) {
      \Drupal::logger('moneylink_auth')->error('Unexpected error during getSalaInfo: @message', [
        '@message' => $e->getMessage(),
      ]);
      
      return [
        'status' => 0,
        'message' => 'Error fetching sala info: ' . $e->getMessage(),
      ];
    }
  }

  /**
   * Crear una nueva sala.
   * 
   * @param string $name
   *   El nombre de la sala a crear.
   * 
   * @return array
   *   Respuesta de la API con los datos de la sala creada o error.
   */
  public function createSala(string $name): array
  {
    $token = $this->storeService->getAuthToken();

    if (!$token) {
      return ['status' => 0, 'message' => 'User not authenticated.'];
    }

    try {
      $response = $this->httpClient->post(self::API_BASE_URL, [
        'headers' => [
          'Content-Type' => 'application/json',
          'Accept' => 'application/json',
          'Authorization' => 'Bearer ' . $token,
        ],
        'json' => [
          'name' => $name,
        ],
      ]);

      $data = json_decode($response->getBody()->getContents(), true);

      return $data;
    } catch (\GuzzleHttp\Exception\ClientException $e) {
      // Si es un error 401, limpiar token inválido
      if ($e->getResponse() && $e->getResponse()->getStatusCode() === 401) {
        $this->storeService->clearUserData();
        \Drupal::service('session_manager')->destroy();
        
        \Drupal::logger('moneylink_auth')->warning('API returned 401 during createSala for user @uid', [
          '@uid' => \Drupal::currentUser()->id(),
        ]);
        
        return [
          'status' => 0, 
          'message' => 'Authentication expired. Please log in again.',
          'redirect_to_logout' => true
        ];
      }
      
      \Drupal::logger('moneylink_auth')->error('API error during createSala: @message', [
        '@message' => $e->getMessage(),
      ]);
      
      return [
        'status' => 0,
        'message' => 'Error creating sala: ' . $e->getMessage(),
      ];
    } catch (\Exception $e) {
      \Drupal::logger('moneylink_auth')->error('Unexpected error during createSala: @message', [
        '@message' => $e->getMessage(),
      ]);
      
      return [
        'status' => 0,
        'message' => 'Error creating sala: ' . $e->getMessage(),
      ];
    }
  }

  /**
   * Elimina una sala específica del usuario.
   * 
   * @param string $salaId
   *   ID de la sala a eliminar.
   * 
   * @return array
   *   Respuesta de la API con el resultado de la eliminación o error.
   */
  public function deleteSala(string $salaId): array
  {
    $token = $this->storeService->getAuthToken();

    if (!$token) {
      return ['status' => 0, 'message' => 'User not authenticated.'];
    }

    try {
      $response = $this->httpClient->delete(self::API_BASE_URL . '/' . $salaId, [
        'headers' => [
          'Content-Type' => 'application/json',
          'Accept' => 'application/json',
          'Authorization' => 'Bearer ' . $token,
        ],
      ]);

      $data = json_decode($response->getBody()->getContents(), true);

      \Drupal::logger('moneylink_auth')->info('Sala @sala_id deleted successfully by user @uid', [
        '@sala_id' => $salaId,
        '@uid' => \Drupal::currentUser()->id(),
      ]);

      return $data;
    } catch (\GuzzleHttp\Exception\ClientException $e) {
      // Si es un error 401, limpiar token inválido
      if ($e->getResponse() && $e->getResponse()->getStatusCode() === 401) {
        $this->storeService->clearUserData();
        \Drupal::service('session_manager')->destroy();
        
        \Drupal::logger('moneylink_auth')->warning('API returned 401 during deleteSala for user @uid', [
          '@uid' => \Drupal::currentUser()->id(),
        ]);
        
        return [
          'status' => 0, 
          'message' => 'Authentication expired. Please log in again.',
          'redirect_to_logout' => true
        ];
      }
      
      \Drupal::logger('moneylink_auth')->error('API error during deleteSala: @message', [
        '@message' => $e->getMessage(),
      ]);
      
      return [
        'status' => 0,
        'message' => 'Error deleting sala: ' . $e->getMessage(),
      ];
    } catch (\Exception $e) {
      \Drupal::logger('moneylink_auth')->error('Unexpected error during deleteSala: @message', [
        '@message' => $e->getMessage(),
      ]);
      
      return [
        'status' => 0,
        'message' => 'Error deleting sala: ' . $e->getMessage(),
      ];
    }
  }

  /**
   * Actualiza el nombre de una sala específica.
   * 
   * @param string $salaId
   *   ID de la sala a actualizar.
   * @param string $newName
   *   Nuevo nombre para la sala.
   * 
   * @return array
   *   Respuesta de la API con el resultado de la actualización o error.
   */
  public function updateSala(string $salaId, string $newName): array
  {
    $token = $this->storeService->getAuthToken();

    if (!$token) {
      return ['status' => 0, 'message' => 'User not authenticated.'];
    }

    try {
      $response = $this->httpClient->patch(self::API_BASE_URL . '/' . $salaId, [
        'headers' => [
          'Content-Type' => 'application/json',
          'Accept' => 'application/json',
          'Authorization' => 'Bearer ' . $token,
        ],
        'json' => [
          'name' => $newName,
        ],
      ]);

      $data = json_decode($response->getBody()->getContents(), true);

      \Drupal::logger('moneylink_auth')->info('Sala @sala_id updated successfully by user @uid. New name: @name', [
        '@sala_id' => $salaId,
        '@uid' => \Drupal::currentUser()->id(),
        '@name' => $newName,
      ]);

      return $data;
    } catch (\GuzzleHttp\Exception\ClientException $e) {
      // Si es un error 401, limpiar token inválido
      if ($e->getResponse() && $e->getResponse()->getStatusCode() === 401) {
        $this->storeService->clearUserData();
        \Drupal::service('session_manager')->destroy();
        
        \Drupal::logger('moneylink_auth')->warning('API returned 401 during updateSala for user @uid', [
          '@uid' => \Drupal::currentUser()->id(),
        ]);
        
        return [
          'status' => 0, 
          'message' => 'Authentication expired. Please log in again.',
          'redirect_to_logout' => true
        ];
      }
      
      \Drupal::logger('moneylink_auth')->error('API error during updateSala: @message', [
        '@message' => $e->getMessage(),
      ]);
      
      return [
        'status' => 0,
        'message' => 'Error updating sala: ' . $e->getMessage(),
      ];
    } catch (\Exception $e) {
      \Drupal::logger('moneylink_auth')->error('Unexpected error during updateSala: @message', [
        '@message' => $e->getMessage(),
      ]);
      
      return [
        'status' => 0,
        'message' => 'Error updating sala: ' . $e->getMessage(),
      ];
    }
  }
}
