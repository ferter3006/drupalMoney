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
    
    // Siempre mostrar la página de inicio, independientemente del estado de login
    return [
      '#theme' => 'moneylink_home',
      '#user_data' => $userData,
      '#is_logged_in' => !empty($userData),
      '#cache' => ['max-age' => 0],
      '#attached' => [
        'library' => [
          'moneylink_userpanel/moneylink_pages',
        ],
      ],
    ];
  }

}