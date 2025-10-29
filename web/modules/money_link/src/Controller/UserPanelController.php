<?php

declare(strict_types=1);

namespace Drupal\money_link\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for money_link routes.
 */
final class UserPanelController extends ControllerBase
{

  /**
   * Builds the response.
   */
  public function __invoke(): array
  {

    $store = \Drupal::service('tempstore.private')->get('hola_mundo');

    $user = $store->get('user_data') ?? [];
    $token = $store->get('auth_token') ?? '';

    return [
      '#theme' => 'userpaneltemplate',
      '#foo' => 'Este es el contenido de foo en el User Panel',
      '#user' => $user,
      '#token' => $token,
      '#cache' => ['max-age' => 0]
    ];
  }
}
