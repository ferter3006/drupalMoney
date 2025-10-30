<?php

declare(strict_types=1);

namespace Drupal\moneylink_store;

use Drupal\Core\TempStore\PrivateTempStoreFactory;

/**
 * Servicio para gestionar el almacenamiento temporal de Moneylink.
 */
final class MoneyLinkStoreService {

  private const STORE_KEY = 'ml_state';

  public function __construct(
    private PrivateTempStoreFactory $tempStoreFactory,
  ) {}

  /**
   * Obtiene los datos del usuario.
   */
  public function getUserData(): ?array {
    return $this->get('user_data');
  }

  /**
   * Guarda los datos del usuario.
   */
  public function setUserData(array $data): void {
    $this->set('user_data', $data);
  }

  /**
   * Obtiene el token de autenticación.
   */
  public function getAuthToken(): ?string {
    return $this->get('auth_token');
  }

  /**
   * Guarda el token de autenticación.
   */
  public function setAuthToken(string $token): void {
    $this->set('auth_token', $token);
  }

  /**
   * Limpia todos los datos del usuario.
   */
  public function clearUserData(): void {
    $store = $this->tempStoreFactory->get(self::STORE_KEY);
    $store->delete('user_data');
    $store->delete('auth_token');
  }

  /**
   * Obtiene un valor del store.
   */
  private function get(string $key): mixed {
    return $this->tempStoreFactory->get(self::STORE_KEY)->get($key);
  }

  /**
   * Guarda un valor en el store.
   */
  private function set(string $key, mixed $value): void {
    $this->tempStoreFactory->get(self::STORE_KEY)->set($key, $value);
  }
}