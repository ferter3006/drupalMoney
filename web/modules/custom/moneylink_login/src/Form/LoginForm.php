<?php

namespace Drupal\moneylink_login\Form;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\moneylink_auth\MoneyLinkAuthService;

/**
 * Provides a login form.
 */
class LoginForm extends FormBase
{
    /**
     * {@inheritdoc}
     */
    private MoneyLinkAuthService $authService;

    public function __construct(MoneyLinkAuthService $auth_service)
    {
        $this->authService = $auth_service;
    }

    public static function create(ContainerInterface $container): self
    {
        return new static(
            $container->get('moneylink_auth')
        );
    }


    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'login_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        // Email field
        $form['email'] = [
            '#type' => 'email',
            '#title' => $this->t('Email'),
            '#required' => TRUE,
        ];

        // Password field
        $form['password'] = [
            '#type' => 'password',
            '#title' => $this->t('Password'),
            '#required' => TRUE,
        ];

        // Submit button
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Enviar'),
        ];


        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state): void
    {
        $email = $form_state->getValue('email');
        $password = $form_state->getValue('password');

        try {
            $this->sendLoginRequest($email, $password, $form_state);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            \Drupal::logger('moneylink_auth')->error('Login API error: @message', [
                '@message' => $e->getMessage(),
            ]);
            
            $this->handleApiError($e);
        } catch (\Exception $e) {
            $this->handleGenericError($e);
        }
    }

    /**
     * Sends a login request to the API.
     */
    private function sendLoginRequest(string $email, string $password, FormStateInterface $form_state): void
    {
        $result = $this->authService->login($email, $password);        

        // Redirect based on user role
        if (!empty($result['user']['role']) && $result['user']['role'] === 'admin') {
            // Redirect to admin panel
            $form_state->setRedirect('moneylink_adminpanel');
        } else {
            // Redirect to user panel
            $form_state->setRedirect('moneylink_userpanel');
        }
    }

    /**
     * Handles API errors.
     */
    private function handleApiError(\GuzzleHttp\Exception\ClientException $e): void
    {
        $response = $e->getResponse();
        $body = $response ? $response->getBody()->getContents() : '';
        $data = json_decode($body, TRUE);

        $msg = $data['message'] ?? 'No se pudo conectar con la API.';
        $this->messenger()->addError($this->t('iep! @msg', ['@msg' => $msg]));
    }

    private function handleGenericError(\Exception $e): void
    {
        $this->messenger()->addError($this->t('Error inesperado: @msg', ['@msg' => $e->getMessage()]));
    }
}
