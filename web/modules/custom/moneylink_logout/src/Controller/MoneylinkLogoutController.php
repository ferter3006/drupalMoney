<?php

declare(strict_types=1);

namespace Drupal\moneylink_logout\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\moneylink_auth\MoneyLinkAuthService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Returns responses for Moneylink logout routes.
 */
final class MoneylinkLogoutController extends ControllerBase
{

  private MoneyLinkAuthService $authService;

  public function __construct(MoneyLinkAuthService $auth_service)
  {
    $this->authService = $auth_service;
  }

  public static function create(ContainerInterface $container): self
  {
    return new static(
      $container->get('moneylink_auth')
    );
  }

  /**
   * Builds the response.
   */
  public function __invoke(): RedirectResponse
  {
    // Realizar logout de MoneyLink
    $response = $this->authService->logout();

    // Realizar logout de Drupal independientemente del resultado de MoneyLink
    if (\Drupal::currentUser()->isAuthenticated()) {
      \Drupal::service('user.auth')->logout();
    }

    if (!empty($response['status']) && $response['status'] == 1) {
      $this->messenger()->addStatus($this->t('You have been logged out successfully.'));
    } else {
      // No mostrar error si el logout local fue exitoso
      $message = $response['message'] ?? $this->t('Session cleared successfully.');
      $this->messenger()->addStatus($this->t('Logout completed: @message', ['@message' => $message]));
    }

    // Redirigir a home después del logout
    return new RedirectResponse('/');
  }
}
