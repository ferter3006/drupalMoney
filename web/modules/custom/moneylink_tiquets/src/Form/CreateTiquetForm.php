<?php

declare(strict_types=1);

namespace Drupal\moneylink_tiquets\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\moneylink_tiquets\Service\TiquetsFormHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Formulario para crear un nuevo tiquet.
 */
class CreateTiquetForm extends FormBase
{

    /**
     * Constructor.
     */
    public function __construct(
        private TiquetsFormHelper $formHelper,
    ) {}

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('moneylink_tiquets.form_helper')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'moneylink_tiquets_create_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, $sala_id = NULL, $mes = NULL): array
    {
        // Obtener sala_name del query parameter si se pasa
        $request = \Drupal::request();
        $sala_name = $request->query->get('sala_name', 'Sala');

        // Añadir datos al storage
        $storage = $form_state->getStorage();
        $storage['sala_info'] = [
            'sala_id' => $sala_id,
            'sala_name' => $sala_name,
            'mes' => $mes,
            'loaded_at' => date('Y-m-d H:i:s'),
        ];
        $form_state->setStorage($storage);

        dump($form_state->getStorage());

        // Verificar que tenemos sala_id
        if (!$sala_id || !is_numeric($sala_id)) {
            $this->messenger()->addError($this->t('ID de sala requerido y debe ser numérico.'));
            return [];
        }

        // Log para debug
        \Drupal::logger('moneylink_tiquets')->info('Creando formulario para sala ID: @sala_id', [
            '@sala_id' => $sala_id,
        ]);

        // Obtener categorías
        $categoriesResult = $this->formHelper->getCategories();

        // Verificar si hay redirección al logout
        if (isset($categoriesResult['status']) && $categoriesResult['status'] === 0 && !empty($categoriesResult['redirect_to_logout'])) {
            $form_state->setResponse(new RedirectResponse('/ml/logout'));
            return [];
        }

        $categoryOptions = [];
        if (!empty($categoriesResult['categories'])) {
            foreach ($categoriesResult['categories'] as $category) {
                $categoryOptions[$category['id']] = $category['name'];
            }
        }

        // Construcción del formulario
        $form['#theme'] = 'moneylink_tiquets_create_form';
        $form['#attached']['library'][] = 'moneylink_tiquets/create_form';

        $form['sala_id'] = [
            '#type' => 'hidden',
            '#value' => $sala_id,
        ];

        // Agregar sala_name como elemento markup para usarlo en el template
        $form['sala_name'] = [
            '#type' => 'markup',
            '#markup' => $sala_name,
        ];

        $form['category_id'] = [
            '#type' => 'select',
            '#title' => $this->t('Categoría'),
            '#options' => $categoryOptions,
            '#required' => TRUE,
            '#empty_option' => $this->t('- Selecciona una categoría -'),
            '#attributes' => ['class' => ['form-select']],
        ];

        $form['es_ingreso'] = [
            '#type' => 'radios',
            '#title' => '',
            '#options' => [
                1 => $this->t('Ingreso'),
                0 => $this->t('Gasto'),
            ],
            '#required' => TRUE,
            '#default_value' => 1,
            '#attributes' => ['class' => ['movement-type-radios']],
            '#title_display' => 'invisible',
        ];

        $form['amount'] = [
            '#type' => 'number',
            '#title' => $this->t('Cantidad'),
            '#required' => TRUE,
            '#step' => 0.01,
            '#min' => 0.01,
            '#attributes' => [
                'placeholder' => '0.00',
                'class' => ['form-number'],
            ],
        ];

        $form['description'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Descripción'),
            '#required' => TRUE,
            '#rows' => 4,
            '#attributes' => [
                'placeholder' => 'Describe el motivo de este movimiento financiero...',
                'class' => ['form-textarea'],
            ],
        ];

        $form['actions'] = [
            '#type' => 'actions',
        ];

        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Crear Tiquet'),
            '#attributes' => ['class' => ['button', 'button--primary']],
        ];

        $form['actions']['cancel'] = [
            '#type' => 'link',
            '#title' => $this->t('Cancelar'),
            '#url' => \Drupal\Core\Url::fromRoute('moneylink_salas.show', [
                'id' => $sala_id,
                'mes' => $mes ?? date('Y-m')
            ]),
            '#attributes' => ['class' => ['button', 'button--secondary']],            
        ];

        return $form;
    }

    /** {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $amount = $form_state->getValue('amount');

        if ($amount <= 0) {
            $form_state->setErrorByName('amount', $this->t('La cantidad debe ser mayor que 0.'));
        }

        $description = trim($form_state->getValue('description'));
        if (strlen($description) < 10) {
            $form_state->setErrorByName('description', $this->t('La descripción debe tener al menos 10 caracteres.'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $values = $form_state->getValues();

        // Validar que tenemos sala_id
        if (empty($values['sala_id'])) {
            $this->messenger()->addError($this->t('Error: ID de sala no encontrado.'));
            return;
        }

        // Log para debug
        \Drupal::logger('moneylink_tiquets')->info('Enviando tiquet: Sala @sala_id, Categoría @cat, Tipo @tipo, Cantidad @amount', [
            '@sala_id' => $values['sala_id'],
            '@cat' => $values['category_id'],
            '@tipo' => $values['es_ingreso'] ? 'Ingreso' : 'Gasto',
            '@amount' => $values['amount'],
        ]);

        $result = $this->formHelper->createTiquet(
            (int) $values['sala_id'],
            (int) $values['category_id'],
            (bool) $values['es_ingreso'],
            $values['description'],
            (float) $values['amount']
        );

        // Verificar redirección a logout
        if (!empty($result['redirect_to_logout'])) {
            $form_state->setResponse(new RedirectResponse('/ml/logout'));
            return;
        }

        if (!empty($result['status']) && $result['status'] == 1) {
            $this->messenger()->addStatus($this->t('Tiquet creado exitosamente.'));
            $form_state->setRedirect('moneylink_salas.show', [
                'id' => $values['sala_id'],
                'mes' => $form_state->getStorage()['sala_info']['mes'] ?? date('Y-m'),
            ]);
        } else {
            $message = $result['message'] ?? $this->t('Error desconocido al crear el tiquet.');
            $this->messenger()->addError($this->t('Error al crear tiquet: @message', ['@message' => $message]));
        }
    }
}
