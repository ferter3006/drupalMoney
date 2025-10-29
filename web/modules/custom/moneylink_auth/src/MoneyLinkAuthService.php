<?php

declare(strict_types=1);

namespace Drupal\moneylink_auth;

use Drupal\Core\TempStore\PrivateTempStoreFactory;
use GuzzleHttp\Client;

/**
 * @todo Add class description.
 */
final class MoneyLinkAuthService
{


  public function __construct(
    private Client $httpClient,
    private PrivateTempStoreFactory $tempStoreFactory,
  ) {}

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

    $data = json_decode($response->getBody()->getContents(), true);

    if (!empty($data['status']) && $data['status'] == "1") {
      // Store user data and auth token in private temp store
      $store = $this->tempStoreFactory->get('ml_state');

      // Clear previous data
      $store->delete('user_data');
      $store->delete('auth_token');

      // Store new data
      $store->set('user_data', $data['user']);
      $store->set('auth_token', $data['token']);
    }

    return $data;
  }

  public function logout(): array
  {
    $store = $this->tempStoreFactory->get('ml_state');
    $token = $store->get('auth_token');

    try {

      $response = $this->httpClient->post('https://ioc.ferter.es/api/users/logout', [
        'headers' => [
          'Content-Type' => 'application/json',
          'Accept' => 'application/json',
          'Authorization' => 'Bearer ' . $token,
        ],
      ]);

      $data = json_decode($response->getBody()->getContents(), true);

      if (!empty($data['status']) && $data['status'] == "1") {
        // Clear user data and auth token from private temp store
        $store->delete('user_data');
        $store->delete('auth_token');
      }

      return $data;
    } catch (\GuzzleHttp\Exception\ClientException $e) {
      $response = $e->getResponse();
      $body = $response ? $response->getBody()->getContents() : '';
      $data = json_decode($body, TRUE);
      $api_message = $data['message'] ?? 'Unknown API error during logout.';
      // Return the specific API message.
      return ['status' => 0, 'message' => 'Logout failed: ' . $api_message];
    } catch (\Exception $e) {
      // Catch any other unexpected errors and provide a generic message.
      return ['status' => 0, 'message' => 'An unexpected error occurred during logout: ' . $e->getMessage()];
    }
  }
}
