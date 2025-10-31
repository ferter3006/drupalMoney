<?php

declare(strict_types=1);

namespace Drupal\moneylink_auth\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\moneylink_store\MoneyLinkStoreService;
use Symfony\Component\Routing\Route;

/**
 * Custom access check for MoneyLink authenticated routes.
 */
class MoneyLinkAuthAccessCheck implements AccessInterface {

  /**
   * The MoneyLink store service.
   *
   * @var \Drupal\moneylink_store\MoneyLinkStoreService
   */
  protected $storeService;

  /**
   * Constructs a MoneyLinkAuthAccessCheck object.
   *
   * @param \Drupal\moneylink_store\MoneyLinkStoreService $store_service
   *   The MoneyLink store service.
   */
  public function __construct(MoneyLinkStoreService $store_service) {
    $this->storeService = $store_service;
  }

  /**
   * Checks access to MoneyLink routes.
   *
   * @param \Symfony\Component\Routing\Route $route
   *   The route to check against.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The currently logged in account.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  public function access(Route $route, AccountInterface $account) {
    // Verificar si el usuario está autenticado en MoneyLink
    $token = $this->storeService->getAuthToken();
    $userData = $this->storeService->getUserData();
    $isValid = $this->storeService->isTokenValid();
    
    if (!$token || !$userData || !$isValid) {
      // Limpiar datos inválidos
      $this->storeService->clearUserData();
      
      \Drupal::logger('moneylink_auth')->info('Access denied to route @route - user not authenticated in MoneyLink', [
        '@route' => $route->getPath(),
      ]);
      
      return AccessResult::forbidden('User must be authenticated in MoneyLink to access this page.')
        ->addCacheContexts(['session']);
    }
    
    return AccessResult::allowed()
      ->addCacheContexts(['session'])
      ->addCacheTags(['moneylink_auth']);
  }

}