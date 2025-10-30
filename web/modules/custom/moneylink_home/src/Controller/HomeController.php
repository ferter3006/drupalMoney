<?php

declare(strict_types=1);

namespace Drupal\moneylink_home\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for the Moneylink homepage.
 */
final class HomeController extends ControllerBase {

  /**
   * Constructor with TempStore injection.
   */
  public function __construct(
    private PrivateTempStoreFactory $tempStoreFactory,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('tempstore.private')
    );
  }

  /**
   * Builds the response for the homepage.
   */
  public function content(): array|RedirectResponse {
    // Verificar si hay datos de usuario
    $store = $this->tempStoreFactory->get('ml_state');
    $userData = $store->get('user_data');
    
    // Si hay usuario logueado, redirigir según rol
    if ($userData && isset($userData['role'])) {
      return $userData['role'] === 'admin' 
        ? new RedirectResponse('/ml/adminpanel')
        : new RedirectResponse('/ml/userpanel');
    }

    // Si no hay usuario, mostrar página de inicio
    return [
      '#theme' => 'moneylink_home',
      '#message' => $this->t('¡Hola mundo desde la página principal de Moneylink!'),
      '#cache' => ['max-age' => 0],
    ];
  }

}