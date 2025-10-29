<?php

declare(strict_types=1);

namespace Drupal\money_link\EventSubscriber;

use Drupal;
use Drupal\Core\Url as CoreUrl;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @todo Add description for this subscriber.
 */
final class RedirectIfLoggedInSubscriber implements EventSubscriberInterface
{

  /**
   * Kernel request event handler.
   */
  public function onKernelRequest(RequestEvent $event): void
  {
    // @todo Place your code here.
  }

  /**
   * Kernel response event handler.
   */
  public function onKernelResponse(ResponseEvent $event): void
  {
    // @todo Place your code here.
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array
  {
    return [
      KernelEvents::REQUEST => ['redirectIfLoggedIn', 30],
    ];
  }

  public function redirectIfLoggedIn(RequestEvent $event): void
  {
    $request = $event->getRequest();
    $route_name = Drupal::routeMatch()->getRouteName();

    // Solo actuar en la ruta de login y la home
    if ($route_name == 'money_link.login_form' || $route_name == 'money_link.home') {
      $store = Drupal::service('tempstore.private')->get('hola_mundo');
      $token = $store->get('auth_token');
      $user_data = $store->get('user_data');

      // Si esta el token y el rol del usuario, redirigir según el rol
      if ($token && !empty($user_data['role'])) {
        if ($user_data['role'] === 'admin') {
          $response = new RedirectResponse(CoreUrl::fromRoute('money_link.admin_panel')->toString());
        } else {
          $response = new RedirectResponse(CoreUrl::fromRoute('money_link.user_panel')->toString());
        }
        $event->setResponse($response);
      }
    }
  }
}
