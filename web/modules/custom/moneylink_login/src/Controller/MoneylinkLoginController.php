<?php

declare(strict_types=1);

namespace Drupal\moneylink_login\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Drupal\money_link\Form\LoginForm;

/**
 * Returns responses for Moneylink login routes.
 */
final class MoneylinkLoginController extends ControllerBase
{

  /**
   * Builds the response.
   */
  public function __invoke(): array
  {

    $form = \Drupal::formBuilder()->getForm(\Drupal\moneylink_login\Form\LoginForm::class);
    
    return [
      '#theme' => 'logintemplate',
      '#form' => $form,
      '#cache' => ['max-age' => 0],
    ];
  }
}
