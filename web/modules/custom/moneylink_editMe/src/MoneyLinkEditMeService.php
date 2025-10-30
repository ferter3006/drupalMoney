<?php

declare(strict_types=1);

namespace Drupal\moneylink_editMe;

use Drupal\moneylink_store\MoneyLinkStoreService;
use GuzzleHttp\Client;

/**
 * Service for editing user profile in MoneyLink.
 */
final class MoneyLinkEditMeService {

  public function __construct(
    private Client $httpClient,
    private MoneyLinkStoreService $storeService,
  ) {}

  /**
   * Updates a single user field.
   *
   * @param string $field
   *   The field to update ('name', 'email', or 'password').
   * @param string $value
   *   The new value for the field.
   * @return array
   *   The API response.
   */
  public function updateField(string $field, string $value): array {
    $token = $this->storeService->getAuthToken();
    
    if (!$token) {
      return ['status' => 0, 'message' => 'User not authenticated.'];
    }

    try {
      $response = $this->httpClient->patch('https://ioc.ferter.es/api/users/me', [
        'headers' => [
          'Content-Type' => 'application/json',
          'Accept' => 'application/json',
          'Authorization' => 'Bearer ' . $token,
        ],
        'json' => [$field => $value],
      ]);

      $data = json_decode($response->getBody()->getContents(), true);

      if (!empty($data['status']) && $data['status'] == "1") {
        $this->storeService->setUserData($data['user']);
      }

      return $data;
    } catch (\Exception $e) {
      return [
        'status' => 0,
        'message' => 'Error updating profile: ' . $e->getMessage(),
      ];
    }
  }
}