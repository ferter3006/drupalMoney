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

    $build['content'] = [
      '#type' => 'item',
      '#markup' => '<h1>' . $this->t('Hola mundo') . '</h1><p>' . $this->t('Bienvenido al panel de usuario, @name', ['@name' => $user_data['name'] ?? 'Usuario']) . '</p>',
    ];

    return $build;
  }

}
