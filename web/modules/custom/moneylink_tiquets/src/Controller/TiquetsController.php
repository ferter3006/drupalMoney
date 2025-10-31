<?php

declare(strict_types=1);

namespace Drupal\moneylink_tiquets\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controlador para las páginas de tickets.
 */
class TiquetsController extends ControllerBase {

  /**
   * Lista de tickets del usuario.
   */
  public function listTiquets(): array {
    return [
      '#markup' => '<p>' . $this->t('Lista de tickets - Por implementar') . '</p>',
    ];
  }

  /**
   * Vista de un ticket individual.
   */
  public function viewTiquet($tiquet_id): array {
    return [
      '#markup' => '<p>' . $this->t('Vista del ticket @id - Por implementar', ['@id' => $tiquet_id]) . '</p>',
    ];
  }
}