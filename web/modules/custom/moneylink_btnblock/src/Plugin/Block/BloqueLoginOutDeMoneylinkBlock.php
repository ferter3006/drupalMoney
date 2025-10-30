<?php

declare(strict_types=1);

namespace Drupal\moneylink_btnblock\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TempStore\PrivateTempStore;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a bloque login/out de moneylink block.
 */
#[Block(
  id: 'moneylink_btnblock_bloque_login_out_de_moneylink',
  admin_label: new TranslatableMarkup('Bloque login/out de MoneyLink'),
  category: new TranslatableMarkup('Custom'),
)]
final class BloqueLoginOutDeMoneylinkBlock extends BlockBase
{

  /**
   * {@inheritdoc}
   */
  public function build(): array
  {

    $store = \Drupal::service('tempstore.private')->get('ml_state');
    $token = $store->get('auth_token');

    if (!$token) {
      $path = 'internal:/ml/login';
      $text = $this->t('Log in');
      $btnClass = 'btn-primary';
      $containerClass = 'moneylink-login-block';
    } else {
      $path = 'internal:/ml/logout';
      $text = $this->t('Log out');
      $btnClass = 'btn-secondary';
      $containerClass = 'moneylink-logout-block';
    }

    $url = \Drupal\Core\Url::fromUri($path);
    $link = \Drupal\Core\Link::fromTextAndUrl($text, $url)->toRenderable();
    $link['#attributes']['class'] = ['btn', $btnClass];

    return [
      '#type' => 'container',
      '#attributes' => ['class' => [$containerClass]],
      'link' => $link,
    ];
  }



  public function getCacheMaxAge(): int
  {
    return 0;
  }

  public function getCacheContexts(): array
  {
    return ['user'];
  }
}
