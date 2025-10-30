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
    $response = $this->authService->logout();

    if (!empty($response['status']) && $response['status'] == 1) {
      $this->messenger()->addStatus($this->t('You have been logged out successfully.'));
    } else {
      $message = $response['message'] ?? $this->t('An unknown error occurred during logout.');
      $this->messenger()->addError($this->t('Logout failed: @message', ['@message' => $message]));
    }

    // Redirigir a home después del logout
    return new RedirectResponse('/home');
  }
}
