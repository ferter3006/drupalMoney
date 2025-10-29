<?php

declare(strict_types=1);

namespace Drupal\moneylink_login_logout\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Messenger\Messenger;

/**
 * Provides a bloque login logout externo block.
 */
#[Block(
  id: 'moneylink_login_logout',
  admin_label: new TranslatableMarkup('Money Link Login Logout Block'),
  category: new TranslatableMarkup('Custom'),
)]
final class MoneylinkLoginLogoutBlock extends BlockBase implements ContainerFactoryPluginInterface
{

  /**
   * The private tempstore factory.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  protected PrivateTempStoreFactory $tempStoreFactory;

  /**
   * Constructs a new MoneylinkLoginLogoutBlock.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   *   The private tempstore factory.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, PrivateTempStoreFactory $temp_store_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->tempStoreFactory = $temp_store_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): self {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('tempstore.private')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array
  {

    $store = $this->tempStoreFactory->get('ml_state');
    $token = $store->get('auth_token');

    if (!$token) {
      $url = Url::fromUri('internal:/moneylink/login');
      $link = Link::fromTextAndUrl($this->t('Log in'), $url)->toRenderable();
      $link['#attributes']['class'][] = 'btn';
      $link['#attributes']['class'][] = 'btn-primary';
      return [
        '#type' => 'container',
        '#attributes' => ['class' => ['moneylink-login-block']],
        'link' => $link,
      ];
    } else {
      $url = Url::fromUri('internal:/moneylink/logout');
      $link = Link::fromTextAndUrl($this->t('Log out'), $url)->toRenderable();
      $link['#attributes']['class'][] = 'btn';
      $link['#attributes']['class'][] = 'btn-secondary';
      return [
        '#type' => 'container',
        '#attributes' => ['class' => ['moneylink-logout-block']],
        'link' => $link,
      ];
    }
  }

  public function getCacheMaxAge(): int
  {
    return 0;
  }
}
