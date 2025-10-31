<?php

declare(strict_types=1);

namespace Drupal\moneylink_apitiquets;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Drupal\moneylink_store\MoneyLinkStoreService;

/**
 * Servicio para gestionar tiquets a través de la API de MoneyLink.
 */
class MoneyLinkApiTiquetsService {

  // ruta global de la API
  private const API_BASE_URL = 'https://ioc.ferter.es/api/tiquets';

  /**
   * Constructor.
   */
  public function __construct(
    private Client $httpClient,
    private MoneyLinkStoreService $storeService,
  ) {}

  /**
   * Crea un nuevo tiquet.
   * 
   * @param int $salaId
   *   ID de la sala.
   * @param int $categoryId
   *   ID de la categoría.
   * @param bool $esIngreso
   *   True si es un ingreso, false si es un gasto.
   * @param string $description
   *   Descripción del tiquet.
   * @param float $amount
   *   Cantidad del tiquet.
   * 
   * @return array
   *   Respuesta de la API.
   */
  public function createTiquet(int $salaId, int $categoryId, bool $esIngreso, string $description, float $amount): array
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

    $requestData = [
      'sala_id' => $salaId,
      'category_id' => $categoryId,
      'es_ingreso' => $esIngreso,
      'description' => $description,
      'amount' => $amount,
    ];

    try {
      $response = $this->httpClient->request('POST', self::API_BASE_URL, [
        'headers' => [
          'Authorization' => 'Bearer ' . $token,
          'Accept' => 'application/json',
          'Content-Type' => 'application/json',
        ],
        'body' => json_encode($requestData),
        'verify' => false,
        'timeout' => 30,
      ]);

      $data = json_decode($response->getBody()->getContents(), true);
      
      // Log successful API call
      \Drupal::logger('moneylink_apitiquets')->info('Tiquet created successfully. Sala: @sala_id, Amount: @amount, Type: @type', [
        '@sala_id' => $salaId,
        '@amount' => $amount,
        '@type' => $esIngreso ? 'Ingreso' : 'Gasto',
      ]);

      return $data;
    }
    catch (RequestException $e) {
      $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 0;
      
      // Manejar 401 Unauthorized - Token expirado
      if ($statusCode === 401) {
        \Drupal::logger('moneylink_apitiquets')->warning('Token expired while creating tiquet. User will be logged out.');
        
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
      \Drupal::logger('moneylink_apitiquets')->error('Error creating tiquet: @message', [
        '@message' => $e->getMessage(),
      ]);

      return [
        'status' => 0, 
        'message' => 'Failed to create tiquet: ' . $e->getMessage()
      ];
    }
  }
}