<?php

declare(strict_types=1);

namespace Drupal\moneylink_salas\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\moneylink_apisalas\MoneyLinkApiSalasService;
use Drupal\moneylink_store\MoneyLinkStoreService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
    public function salas_home(): array|RedirectResponse
    {
        $userData = (object) $this->storeService->getUserData();
        $result = $this->apiSalasService->getMySalas();

        $salas = [];
        
        // Verificar si la API devolvió salas correctamente
        if (isset($result['status']) && $result['status'] === 0) {
            // Si hay redirección al login, redirigir inmediatamente al logout
            if (!empty($result['redirect_to_logout'])) {
                return new RedirectResponse('/ml/logout');
            }
            
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
        $build['#attached']['library'][] = 'moneylink_userpanel/moneylink_pages';

        return $build;
    }

    /**
     * Builds the response for a specific Sala.
     */
    public function salas_show($id, $mes): array|RedirectResponse
    {
        $salaData = $this->apiSalasService->showSalaById($id, $mes);
        if (isset($salaData['status']) && $salaData['status'] === 0) {
            // Si hay redirección al login, redirigir inmediatamente al logout
            if (!empty($salaData['redirect_to_logout'])) {
                return new RedirectResponse('/ml/logout');
            }
            
            $this->messenger()->addError($this->t('Error fetching sala info: @message', ['@message' => $salaData['message']]));
            return [];
        }
        
        // Obtener el nombre de la sala para el título
        $salaName = isset($salaData['sala']['name']) ? $salaData['sala']['name'] : 'Sala';
        
        dump($salaData);
        $build = [
            '#theme' => 'moneylink_showsala',
            '#message' => $this->t('Detalles de la Sala'),
            '#sala_data' => $salaData,
            '#id' => $id,
            '#mes' => $mes,
            '#cache' => ['max-age' => 0],
            '#title' => $salaName, // Pasar el nombre de la sala al template
        ];
        $build['#attached']['library'][] = 'moneylink_salas/salas';
        $build['#attached']['library'][] = 'moneylink_userpanel/moneylink_pages';

        return $build;
    }

    /**
     * Callback para obtener el título dinámico de la sala.
     */
    public function getSalaTitle($id, $mes)
    {
        $salaData = $this->apiSalasService->showSalaById($id, $mes);
        
        if (isset($salaData['sala']['name'])) {
            return $salaData['sala']['name'];
        }
        
        return $this->t('Detalle de Sala');
    }

    /**
     * Elimina una sala específica.
     */
    public function deleteSala(string $id)
    {
        $result = $this->apiSalasService->deleteSala($id);

        if (!empty($result['redirect_to_logout'])) {
            return new RedirectResponse('/ml/logout');
        }

        if (!empty($result['status']) && $result['status'] == 1) {
            $this->messenger()->addStatus($this->t('Sala eliminada correctamente.'));
        } else {
            $message = $result['message'] ?? 'Error desconocido al eliminar la sala.';
            $this->messenger()->addError($this->t('Error al eliminar sala: @msg', ['@msg' => $message]));
        }

        // Redirigir a la lista de salas después de eliminar
        return new RedirectResponse('/ml/salas');
    }
}
