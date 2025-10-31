<?php

declare(strict_types=1);

namespace Drupal\moneylink_perfil\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\moneylink_editMe\MoneyLinkEditMeService;
use Drupal\moneylink_store\MoneyLinkStoreService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Formulario para editar el email del usuario.
 */
class EditEmailForm extends FormBase
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
        return 'moneylink_perfil_edit_email_form';
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

        $form['current_email'] = [
            '#type' => 'markup',
            '#markup' => '<div class="current-value">
                <strong>Email actual:</strong> ' . $userData->email . '
            </div>',
        ];

        $form['email'] = [
            '#type' => 'email',
            '#title' => $this->t('Nuevo email'),
            '#required' => TRUE,
            '#default_value' => $userData->email,
            '#maxlength' => 255,
            '#attributes' => [
                'placeholder' => 'Introduce tu nuevo email',
                'class' => ['form-input'],
            ],
        ];

        $form['actions'] = [
            '#type' => 'actions',
        ];

        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Guardar Email'),
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
        $email = trim($form_state->getValue('email'));
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $form_state->setErrorByName('email', $this->t('Por favor, introduce un email válido.'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $email = trim($form_state->getValue('email'));

        $result = $this->editMeService->updateField('email', $email);

        if (!empty($result['status']) && $result['status'] == 1) {
            $this->messenger()->addStatus($this->t('Email actualizado correctamente.'));
            $form_state->setRedirect('moneylink_perfil.content');
        } else {
            $message = $result['message'] ?? $this->t('Error desconocido al actualizar el email.');
            $this->messenger()->addError($this->t('Error: @message', ['@message' => $message]));
        }
    }
}