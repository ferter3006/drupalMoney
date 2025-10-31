<?php

declare(strict_types=1);

namespace Drupal\moneylink_tiquets\Service;

use Drupal\moneylink_apitiquets\MoneyLinkApiTiquetsService;
use Drupal\moneylink_apicategorias\MoneyLinkApiCategoriasService;

/**
 * Servicio helper para formularios de tickets.
 */
class TiquetsFormHelper {

  public function __construct(
    private MoneyLinkApiTiquetsService $apiTiquetsService,
    private MoneyLinkApiCategoriasService $apiCategoriasService,
  ) {}

  /**
   * Obtiene las categorías disponibles para el formulario.
   */
  public function getCategories(): array {
    return $this->apiCategoriasService->getCategories();
  }

  /**
   * Crea un nuevo tiquet.
   */
  public function createTiquet(int $salaId, int $categoryId, bool $esIngreso, string $description, float $amount): array {
    return $this->apiTiquetsService->createTiquet($salaId, $categoryId, $esIngreso, $description, $amount);
  }

  /**
   * Obtiene los niveles de prioridad disponibles.
   */
  public function getPriorityLevels(): array {
    return [
      'low' => 'Baja',
      'medium' => 'Media',
      'high' => 'Alta',
      'urgent' => 'Urgente',
    ];
  }
}