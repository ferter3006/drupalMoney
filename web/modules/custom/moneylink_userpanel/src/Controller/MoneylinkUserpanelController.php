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

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
