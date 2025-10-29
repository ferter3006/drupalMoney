<?php

declare(strict_types=1);

namespace Drupal\money_link\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Returns responses for Hola mundo routes.
 */
final class LoginFormController extends ControllerBase
{

  /**
   * Builds the response.
   */
  public function __invoke(): mixed
  {

    // Mostrar el formulario de login
    $form = \Drupal::formBuilder()->getForm(\Drupal\money_link\Form\LoginForm::class);

    return [
      '#theme' => 'loginformtemplate',
      '#loginform' => $form,
      '#cache' => ['max-age' => 0],
    ];
  }
}
