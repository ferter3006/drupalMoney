<?php

declare(strict_types=1);

namespace Drupal\moneylink_salas\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\moneylink_apisalas\MoneyLinkApiSalasService;
use Drupal\moneylink_store\MoneyLinkStoreService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for Moneylink Salas.
 */
final class SalasController extends ControllerBase
{
    /**
     * Constructor.
     */
    public function __construct(
        private MoneyLinkStoreService $storeService,
        private MoneyLinkApiSalasService $apiSalasService
    ) {}

    public static function create(
        ContainerInterface $container,
    ) {
        return new static(
            $container->get('moneylink_store.service'),
            $container->get('moneylink_apisalas.service')
        );
    }

    /**
     * Builds the response for the Salas tab.
     */
    public function salas_home(): array
    {
        $userData = (object) $this->storeService->getUserData();
        $result = $this->apiSalasService->getMySalas();

        $salas = [];
        
        // Verificar si la API devolvió salas correctamente
        if (isset($result['status']) && $result['status'] === 0) {
            // Error de la API
            $this->messenger()->addError($this->t('Error al obtener salas: @msg', ['@msg' => $result['message'] ?? 'Unknown error']));
        } elseif (!empty($result['salas']) && is_array($result['salas'])) {
            // Procesar salas - normalizar nombres de campos
            foreach ($result['salas'] as $sala) {
                $salas[] = [
                    'id' => $sala['sala_id'] ?? null,
                    'name' => $sala['sala_name'] ?? null,
                    'role_id' => $sala['role_id'] ?? null,
                    'role_name' => $sala['role_name'] ?? null,
                ];
            }
        }

        $build = [
            '#theme' => 'moneylink_salas',
            '#message' => $this->t('Salas de ' . ($userData->name ?? '')),
            '#salas' => $salas,
            '#cache' => ['max-age' => 0],
        ];

        $build['#attached']['library'][] = 'moneylink_salas/salas';

        return $build;
    }

    /**
     * Builds the response for a specific Sala.
     */
    public function salas_show($id, $mes): array
    {
        $salaData = $this->apiSalasService->showSalaById($id, $mes);
        if (isset($salaData['status']) && $salaData['status'] === 0) {
            $this->messenger()->addError($this->t('Error fetching sala info: @message', ['@message' => $salaData['message']]));
            return [];
        }
        dump($salaData);
        $build = [
            '#theme' => 'moneylink_showsala',
            '#message' => $this->t('Detalles de la Sala'),
            '#sala_data' => $salaData,
            '#id' => $id,
            '#mes' => $mes,
            '#cache' => ['max-age' => 0],
        ];
        $build['#attached']['library'][] = 'moneylink_salas/salas';

        return $build;
    }
}
