<?php

declare(strict_types=1);

namespace Drupal\moneylink_perfil\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\moneylink_editMe\MoneyLinkEditMeService;
use Drupal\moneylink_store\MoneyLinkStoreService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Formulario para editar el nombre del usuario.
 */
class EditNameForm extends FormBase
{

    /**
     * Constructor.
     */
    public function __construct(
        private MoneyLinkEditMeService $editMeService,
        private MoneyLinkStoreService $storeService,
    ) {}

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('moneylink_editMe.service'),
            $container->get('moneylink_store.service')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'moneylink_perfil_edit_name_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state): array
    {
        $userData = (object) $this->storeService->getUserData();
        
        $form['#prefix'] = '<div class="edit-profile-form">';
        $form['#suffix'] = '</div>';
        $form['#attached']['library'][] = 'moneylink_perfil/edit_forms';

        $form['current_name'] = [
            '#type' => 'markup',
            '#markup' => '<div class="current-value">
                <strong>Nombre actual:</strong> ' . $userData->name . '
            </div>',
        ];

        $form['name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Nuevo nombre'),
            '#required' => TRUE,
            '#default_value' => $userData->name,
            '#maxlength' => 255,
            '#attributes' => [
                'placeholder' => 'Introduce tu nuevo nombre',
                'class' => ['form-input'],
            ],
        ];

        $form['actions'] = [
            '#type' => 'actions',
        ];

        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Guardar Nombre'),
            '#attributes' => ['class' => ['button', 'button--primary']],
        ];

        $form['actions']['cancel'] = [
            '#type' => 'link',
            '#title' => $this->t('Cancelar'),
            '#url' => \Drupal\Core\Url::fromRoute('moneylink_perfil.content'),
            '#attributes' => ['class' => ['button', 'button--secondary']],
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $name = trim($form_state->getValue('name'));
        
        if (strlen($name) < 2) {
            $form_state->setErrorByName('name', $this->t('El nombre debe tener al menos 2 caracteres.'));
        }
        
        if (strlen($name) > 255) {
            $form_state->setErrorByName('name', $this->t('El nombre no puede tener más de 255 caracteres.'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $name = trim($form_state->getValue('name'));

        $result = $this->editMeService->updateField('name', $name);

        if (!empty($result['status']) && $result['status'] == 1) {
            $this->messenger()->addStatus($this->t('Nombre actualizado correctamente.'));
            $form_state->setRedirect('moneylink_perfil.content');
        } else {
            $message = $result['message'] ?? $this->t('Error desconocido al actualizar el nombre.');
            $this->messenger()->addError($this->t('Error: @message', ['@message' => $message]));
        }
    }
}