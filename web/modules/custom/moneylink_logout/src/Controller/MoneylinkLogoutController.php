<?php

declare(strict_types=1);

namespace Drupal\moneylink_logout\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\moneylink_auth\MoneyLinkAuthService;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
  public function __invoke(): array
  {

    $response = $this->authService->logout();

    $template_message = '';
    if (!empty($response['status']) && $response['status'] == 1) {
      $this->messenger()->addStatus($this->t('You have been logged out successfully.'));
      $template_message = $this->t('You have been logged out successfully.');
    } else {
      $message = $response['message'] ?? $this->t('An unknown error occurred during logout.');
      $this->messenger()->addError($this->t('Logout failed: @message', ['@message' => $message]));
      $token = \Drupal::service('tempstore.private')->get('ml_state')->get('auth_token');
      $this->messenger()->addError('otra cosa: ' . $token);
      $template_message = $this->t('Logout failed: @message', ['@message' => $message]);
    }

    return [
      '#theme' => 'logouttemplate',
      '#foo' => $template_message,
    ];
  }
}
