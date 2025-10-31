<?php

declare(strict_types=1);

namespace Drupal\moneylink_apicategorias;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Drupal\moneylink_store\MoneyLinkStoreService;

/**
 * Servicio para gestionar categorías a través de la API de MoneyLink.
 */
class MoneyLinkApiCategoriasService {

  // ruta global de la API
  private const API_BASE_URL = 'https://ioc.ferter.es/api/categories';

  /**
   * Constructor.
   */
  public function __construct(
    private Client $httpClient,
    private MoneyLinkStoreService $storeService,
  ) {}

  /**
   * Obtiene todas las categorías disponibles.
   * 
   * @return array
   *   Lista de categorías o error.
   */
  public function getCategories(): array
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
      $response = $this->httpClient->request('GET', self::API_BASE_URL, [
        'headers' => [
          'Authorization' => 'Bearer ' . $token,
          'Accept' => 'application/json',
          'Content-Type' => 'application/json',
        ],
        'verify' => false,
        'timeout' => 30,
      ]);

      $data = json_decode($response->getBody()->getContents(), true);
      
      // Log successful API call
      \Drupal::logger('moneylink_apicategorias')->info('Categories fetched successfully. Count: @count', [
        '@count' => count($data['categories'] ?? []),
      ]);

      return $data;
    }
    catch (RequestException $e) {
      $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 0;
      
      // Manejar 401 Unauthorized - Token expirado
      if ($statusCode === 401) {
        \Drupal::logger('moneylink_apicategorias')->warning('Token expired while fetching categories. User will be logged out.');
        
        // Limpiar datos de usuario y sesión
        $this->storeService->clearUserData();
        \Drupal::service('session_manager')->destroy();
        
        return [
          'status' => 0, 
          'message' => 'Authentication expired. Please log in again.',
          'redirect_to_logout' => true
        ];
      }
      
      // Log other errors
      \Drupal::logger('moneylink_apicategorias')->error('Error fetching categories: @message', [
        '@message' => $e->getMessage(),
      ]);

      return [
        'status' => 0, 
        'message' => 'Failed to fetch categories: ' . $e->getMessage()
      ];
    }
  }
}