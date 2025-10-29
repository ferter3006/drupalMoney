<?php

namespace Drupal\money_link\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a login form.
 */
class LoginForm extends FormBase
{
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
            $data = $this->sendLoginRequest($email, $password);
            $this->handleApiResponse($data, $form_state);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->handleApiError($e);
        } catch (\Exception $e) {
            $this->handleGenericError($e);
        }
    }

    /**
     * Sends a login request to the API.
     */
    private function sendLoginRequest(string $email, string $password): array
    {
        $client = \Drupal::httpClient();

        $response = $client->post('https://ioc.ferter.es/api/users/login', [
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            'json' => [
                'email' => $email,
                'password' => $password,
            ],
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body, TRUE);

        \Drupal::logger('hola_mundo')->notice('API Raw Response: @resp', ['@resp' => $body]);

        return $data ?? [];
    }

    /**
     * Handles the API response.
     */
    private function handleApiResponse(array $data, FormStateInterface $form_state): void
    {
        if (!empty($data['status']) && $data['status'] == "1") {
            // ✅ Login correcto
            $store = \Drupal::service('tempstore.private')->get('hola_mundo');
            // Vaciamos datos previos
            $store->delete('user_data');
            $store->delete('auth_token');

            // Guardamos nuevos datos
            $store->set('user_data', $data['user']);
            $store->set('auth_token', $data['token']);


            $this->messenger()->addStatus($this->t('Login correcto. El token es: @token', ['@token' => $data['token']]));

            // Verifico si el user es admin o user
            if (!empty($data['user']['role']) && $data['user']['role'] === 'admin') {
                $form_state->setRedirect('money_link.admin_panel');
            } else {
                $form_state->setRedirect('money_link.user_panel');
            }
        } else {
            // ❌ Error con mensaje de la API o genérico
            $msg = $data['message'] ?? 'Error desconocido.';
            $this->messenger()->addError($this->t('Error de login: @msg', ['@msg' => $msg]));
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
        $this->messenger()->addError($this->t('@msg', ['@msg' => $msg]));

        \Drupal::logger('hola_mundo')->error('API Error: @msg', ['@msg' => $body ?: $e->getMessage()]);
    }

    private function handleGenericError(\Exception $e): void
    {
        $this->messenger()->addError($this->t('Error inesperado: @msg', ['@msg' => $e->getMessage()]));
        \Drupal::logger('hola_mundo')->error('Unexpected error: @msg', ['@msg' => $e->getMessage()]);
    }
}
