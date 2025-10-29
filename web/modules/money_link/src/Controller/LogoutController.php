<?php

declare(strict_types=1);

namespace Drupal\money_link\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for money_link routes.
 */
final class LogoutController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function __invoke(): array {

    $store = \Drupal::service('tempstore.private')->get('ml_state');
    $store->delete('auth_token');
    $store->delete('user_data');    

    return [
      '#theme' => 'logouttemplate',
      '#foo' => 'You have been logged out successfully.',
      '#cache' => ['max-age' => 0]
    ];
  }

}
