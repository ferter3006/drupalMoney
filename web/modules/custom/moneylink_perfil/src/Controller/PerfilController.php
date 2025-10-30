<?php

declare(strict_types=1);

namespace Drupal\moneylink_perfil\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\moneylink_store\MoneyLinkStoreService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for Moneylink Perfil.
 */
final class PerfilController extends ControllerBase
{

  /**
   * Constructor.
   */
  public function __construct(
    private MoneyLinkStoreService $storeService,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('moneylink_store.service')
    );
  }

  /**
   * Builds the response for the Perfil tab.
   */
  public function __invoke(): array
  {
    $userData = $this->storeService->getUserData();

    return [
      '#theme' => 'moneylink_perfil',
      '#user_data' => $userData,
      '#cache' => ['max-age' => 0],
    ];
  }
}
