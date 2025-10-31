<?php

declare(strict_types=1);

namespace Drupal\moneylink_perfil\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\moneylink_editMe\MoneyLinkEditMeService;
use Drupal\moneylink_store\MoneyLinkStoreService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Formulario para editar la contraseña del usuario.
 */
class EditPasswordForm extends FormBase
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
        return 'moneylink_perfil_edit_password_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state): array
    {
        $form['#prefix'] = '<div class="edit-profile-form">';
        $form['#suffix'] = '</div>';
        $form['#attached']['library'][] = 'moneylink_perfil/edit_forms';

        $form['info'] = [
            '#type' => 'markup',
            '#markup' => '<div class="current-value">
                <strong>Cambiar contraseña:</strong> Introduce tu nueva contraseña de forma segura
            </div>',
        ];

        $form['password'] = [
            '#type' => 'password',
            '#title' => $this->t('Nueva contraseña'),
            '#required' => TRUE,
            '#attributes' => [
                'placeholder' => 'Introduce tu nueva contraseña',
                'class' => ['form-input'],
            ],
        ];

        $form['password_confirm'] = [
            '#type' => 'password',
            '#title' => $this->t('Confirmar nueva contraseña'),
            '#required' => TRUE,
            '#attributes' => [
                'placeholder' => 'Confirma tu nueva contraseña',
                'class' => ['form-input'],
            ],
        ];

        $form['actions'] = [
            '#type' => 'actions',
        ];

        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Cambiar Contraseña'),
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
        $password = $form_state->getValue('password');
        $password_confirm = $form_state->getValue('password_confirm');
        
        if (strlen($password) < 6) {
            $form_state->setErrorByName('password', $this->t('La contraseña debe tener al menos 6 caracteres.'));
        }
        
        if ($password !== $password_confirm) {
            $form_state->setErrorByName('password_confirm', $this->t('Las contraseñas no coinciden.'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $password = $form_state->getValue('password');

        $result = $this->editMeService->updateField('password', $password);

        if (!empty($result['status']) && $result['status'] == 1) {
            $this->messenger()->addStatus($this->t('Contraseña actualizada correctamente.'));
            $form_state->setRedirect('moneylink_perfil.content');
        } else {
            $message = $result['message'] ?? $this->t('Error desconocido al actualizar la contraseña.');
            $this->messenger()->addError($this->t('Error: @message', ['@message' => $message]));
        }
    }
}