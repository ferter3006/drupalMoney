<?php

declare(strict_types=1);

namespace Drupal\moneylink_salas\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\moneylink_apisalas\MoneyLinkApiSalasService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form to create a new sala.
 */
final class CreateSalaForm extends FormBase
{

    /**
     * The API Salas service.
     *
     * @var \Drupal\moneylink_apisalas\MoneyLinkApiSalasService
     */
    protected MoneyLinkApiSalasService $apiSalasService;

    /**
     * Constructor.
     */
    public function __construct(MoneyLinkApiSalasService $apiSalasService)
    {
        $this->apiSalasService = $apiSalasService;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('moneylink_apisalas.service')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId(): string
    {
        return 'moneylink_salas_create_sala';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state): array
    {
        $form['name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Nombre de la sala'),
            '#description' => $this->t('Introduce el nombre de la nueva sala.'),
            '#required' => TRUE,
            '#maxlength' => 255,
        ];

        $form['actions'] = [
            '#type' => 'actions',
        ];

        $form['actions']['cancel'] = [
            '#type' => 'link',
            '#title' => $this->t('Cancelar'),
            '#url' => \Drupal\Core\Url::fromRoute('moneylink_salas.content'),
            '#attributes' => [
                'class' => ['button'],
            ],
        ];
        
        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Crear sala'),
            '#button_type' => 'primary',
        ];


        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state): void
    {
        $name = $form_state->getValue('name');

        $result = $this->apiSalasService->createSala($name);

        if (isset($result['status']) && $result['status'] === 0) {
            $this->messenger()->addError(
                $this->t('Error al crear la sala: @message', ['@message' => $result['message'] ?? 'Unknown error'])
            );
        } else {
            $this->messenger()->addStatus($this->t('Sala "@name" creada correctamente.', ['@name' => $name]));

            // Redirect to salas list
            $form_state->setRedirect('moneylink_salas.content');
        }
    }
}
