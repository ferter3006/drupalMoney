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
    } catch (\Exception $e) {
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
    } catch (\Exception $e) {
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
    } catch (\Exception $e) {
      return [
        'status' => 0,
        'message' => 'Error creating sala: ' . $e->getMessage(),
      ];
    }
  }
}
