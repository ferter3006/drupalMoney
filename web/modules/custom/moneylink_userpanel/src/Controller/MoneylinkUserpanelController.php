<?php

declare(strict_types=1);

namespace Drupal\moneylink_userpanel\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Moneylink userpanel routes.
 */
final class MoneylinkUserpanelController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function __invoke(): array {
    // Obtener datos del usuario
    $store_service = \Drupal::service('moneylink_store.service');
    $user_data = $store_service->getUserData();
    
    if (!$user_data) {
      // Redirigir al login si no hay datos
      throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('Please log in to access your panel.');
    }

    return [
      '#theme' => 'moneylink_userpanel',
      '#user_data' => $user_data,
      '#cache' => ['max-age' => 0],
      '#attached' => [
        'library' => [
          'moneylink_userpanel/moneylink_pages',
        ],
      ],
    ];
  }

}
