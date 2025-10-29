<?php

declare(strict_types=1);

namespace Drupal\money_link\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for money_link routes.
 */
final class HomeController extends ControllerBase
{

  /**
   * Builds the response.
   */
  public function __invoke(): mixed
  {
   

    return [
      '#theme' => 'hometemplate'
    ];
  }
}
