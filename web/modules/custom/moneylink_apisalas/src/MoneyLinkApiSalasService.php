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

  public function __construct(
    private Client $httpClient,
    private MoneyLinkStoreService $storeService,
  ) {}

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
      $response = $this->httpClient->get('https://ioc.ferter.es/api/salas/' . $salaId . '/' . $mes, [
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
}
