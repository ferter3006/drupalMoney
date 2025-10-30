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
        $salas = [];

        if (!empty($userData->salas) && is_iterable($userData->salas)) {
            foreach ($userData->salas as $sala) {
                $salas[] = [
                    'id' => $sala['sala_id'],
                    'name' => $sala['sala_name']
                ];
            }
        } else {
            echo 'No hay salas disponibles.';
        }

        dump($salas);

        $build = [
            '#theme' => 'moneylink_salas',
            '#message' => $this->t('Salas de ' . $userData->name),
            '#user_data' => $userData,
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
