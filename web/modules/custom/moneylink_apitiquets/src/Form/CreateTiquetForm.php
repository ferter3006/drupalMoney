<?php

declare(strict_types=1);

namespace Drupal\moneylink_apitiquets\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\moneylink_apitiquets\MoneyLinkApiTiquetsService;
use Drupal\moneylink_apicategorias\MoneyLinkApiCategoriasService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Formulario para crear un nuevo tiquet.
 */
class CreateTiquetForm extends FormBase {

  /**
   * Constructor.
   */
  public function __construct(
    private MoneyLinkApiTiquetsService $apiTiquetsService,
    private MoneyLinkApiCategoriasService $apiCategoriasService,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('moneylink_apitiquets.service'),
      $container->get('moneylink_apicategorias.service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'moneylink_create_tiquet_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $sala_id = NULL): array {
    // Obtener categorías
    $categoriesResult = $this->apiCategoriasService->getCategories();
    
    // Verificar si hay redirección al login
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

    // Guardar sala_id en form state
    $form_state->set('sala_id', $sala_id);

    $form['sala_info'] = [
      '#type' => 'item',
      '#markup' => '<div style="background: #f0f8ff; padding: 15px; border-radius: 8px; margin-bottom: 20px;"><strong>📝 Creando tiquet para Sala ID: ' . $sala_id . '</strong></div>',
    ];

    $form['type'] = [
      '#type' => 'radios',
      '#title' => $this->t('Tipo de movimiento'),
      '#options' => [
        '1' => $this->t('💰 Ingreso'),
        '0' => $this->t('💸 Gasto'),
      ],
      '#default_value' => '0',
      '#required' => TRUE,
      '#attributes' => [
        'class' => ['form-radios--type'],
      ],
    ];

    $form['category_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Categoría'),
      '#options' => ['' => $this->t('- Selecciona una categoría -')] + $categoryOptions,
      '#required' => TRUE,
      '#description' => $this->t('Selecciona la categoría que mejor describa este movimiento.'),
    ];

    $form['description'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Descripción'),
      '#required' => TRUE,
      '#maxlength' => 255,
      '#description' => $this->t('Describe brevemente este movimiento (ej: "Pizza", "Salario", "Gasolina").'),
      '#attributes' => [
        'placeholder' => $this->t('Ej: Pizza para la cena'),
      ],
    ];

    $form['amount'] = [
      '#type' => 'number',
      '#title' => $this->t('Cantidad'),
      '#required' => TRUE,
      '#min' => 0.01,
      '#step' => 0.01,
      '#description' => $this->t('Cantidad en euros (usar punto para decimales).'),
      '#attributes' => [
        'placeholder' => $this->t('0.00'),
      ],
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('💾 Crear Tiquet'),
      '#button_type' => 'primary',
      '#attributes' => [
        'class' => ['button', 'button--primary'],
      ],
    ];

    $form['actions']['cancel'] = [
      '#type' => 'link',
      '#title' => $this->t('❌ Cancelar'),
      '#url' => \Drupal\Core\Url::fromRoute('moneylink_salas.show', [
        'id' => $sala_id,
        'mes' => date('n'),
      ]),
      '#attributes' => [
        'class' => ['button'],
      ],
    ];

    // Agregar CSS personalizado
    $form['#attached']['html_head'][] = [
      [
        '#tag' => 'style',
        '#value' => '
          .form-radios--type .form-item {
            display: inline-block;
            margin-right: 30px;
          }
          .form-radios--type input[type="radio"] {
            margin-right: 8px;
          }
          .form-item-amount input {
            width: 150px;
          }
        ',
      ],
      'create_tiquet_form_styles'
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $salaId = (int) $form_state->get('sala_id');
    $categoryId = (int) $form_state->getValue('category_id');
    $esIngreso = (bool) $form_state->getValue('type');
    $description = trim($form_state->getValue('description'));
    $amount = (float) $form_state->getValue('amount');

    $result = $this->apiTiquetsService->createTiquet($salaId, $categoryId, $esIngreso, $description, $amount);

    if (!empty($result['redirect_to_logout'])) {
      $form_state->setResponse(new RedirectResponse('/ml/logout'));
      return;
    }

    if (!empty($result['status']) && $result['status'] == 1) {
      $tipoTexto = $esIngreso ? 'ingreso' : 'gasto';
      $this->messenger()->addStatus($this->t('¡Tiquet creado correctamente! Se registró un @tipo de €@amount en la categoría seleccionada.', [
        '@tipo' => $tipoTexto,
        '@amount' => number_format($amount, 2),
      ]));
    } else {
      $message = $result['message'] ?? 'Error desconocido al crear el tiquet.';
      $this->messenger()->addError($this->t('Error al crear tiquet: @msg', ['@msg' => $message]));
    }

    // Redirigir de vuelta a la vista de la sala
    $form_state->setRedirect('moneylink_salas.show', [
      'id' => $salaId,
      'mes' => date('n')
    ]);
  }
}