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
      $url = \Drupal\Core\Url::fromUri('internal:/ml/login');
      $link = \Drupal\Core\Link::fromTextAndUrl($this->t('Log in'), $url)->toRenderable();
      $link['#attributes']['class'][] = 'btn';
      $link['#attributes']['class'][] = 'btn-primary';
      return [
        '#type' => 'container',
        '#attributes' => ['class' => ['moneylink-login-block']],
        'link' => $link,
      ];
    } else {
      $url = \Drupal\Core\Url::fromUri('internal:/ml/logout');
      $link = \Drupal\Core\Link::fromTextAndUrl($this->t('Log out'), $url)->toRenderable();
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
