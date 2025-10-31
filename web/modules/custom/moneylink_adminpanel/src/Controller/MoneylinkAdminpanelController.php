<?php

declare(strict_types=1);

namespace Drupal\moneylink_adminpanel\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Moneylink adminpanel routes.
 */
final class MoneylinkAdminpanelController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function __invoke(): array {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    $build['#attached']['library'][] = 'moneylink_userpanel/moneylink_pages';

    return $build;
  }

}
