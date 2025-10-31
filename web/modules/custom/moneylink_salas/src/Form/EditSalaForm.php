<?php

declare(strict_types=1);

namespace Drupal\moneylink_salas\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\moneylink_apisalas\MoneyLinkApiSalasService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Formulario para editar el nombre de una sala.
 */
final class EditSalaForm extends FormBase {

  /**
   * The MoneyLink API Salas service.
   *
   * @var \Drupal\moneylink_apisalas\MoneyLinkApiSalasService
   */
  protected $apiSalasService;

  /**
   * Constructor.
   */
  public function __construct(MoneyLinkApiSalasService $api_salas_service) {
    $this->apiSalasService = $api_salas_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('moneylink_apisalas.service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'moneylink_salas_edit_sala';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL): array {
    // Obtener datos actuales de la sala
    $salaData = $this->apiSalasService->showSalaById($id, date('n'));
    
    // Verificar si hay redirección al login
    if (isset($salaData['status']) && $salaData['status'] === 0 && !empty($salaData['redirect_to_logout'])) {
      $form_state->setResponse(new RedirectResponse('/ml/logout'));
      return [];
    }
    
    $currentName = isset($salaData['sala']['name']) ? $salaData['sala']['name'] : '';

    // Guardar el ID en el form state
    $form_state->set('sala_id', $id);

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre de la sala'),
      '#default_value' => $currentName,
      '#required' => TRUE,
      '#maxlength' => 100,
      '#description' => $this->t('Introduce el nuevo nombre para esta sala.'),
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Actualizar'),
      '#button_type' => 'primary',
    ];

    $form['actions']['cancel'] = [
      '#type' => 'link',
      '#title' => $this->t('Cancelar'),
      '#url' => \Drupal\Core\Url::fromRoute('moneylink_salas.show', [
        'id' => $id,
        'mes' => date('n')
      ]),
      '#attributes' => [
        'class' => ['button'],
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $id = $form_state->get('sala_id');
    $newName = $form_state->getValue('name');

    $result = $this->apiSalasService->updateSala($id, $newName);

    if (!empty($result['redirect_to_logout'])) {
      $form_state->setResponse(new RedirectResponse('/ml/logout'));
      return;
    }

    if (!empty($result['status']) && $result['status'] == 1) {
      $this->messenger()->addStatus($this->t('Sala actualizada correctamente.'));
    } else {
      $message = $result['message'] ?? 'Error desconocido al actualizar la sala.';
      $this->messenger()->addError($this->t('Error al actualizar sala: @msg', ['@msg' => $message]));
    }

    // Redirigir de vuelta a la vista de la sala
    $form_state->setRedirect('moneylink_salas.show', [
      'id' => $id,
      'mes' => date('n')
    ]);
  }

}