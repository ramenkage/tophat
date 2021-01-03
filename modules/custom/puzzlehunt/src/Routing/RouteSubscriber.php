<?php

namespace Drupal\puzzlehunt\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('jeditable.ajax_save')) {
      $route->setDefault(
        '_controller',
        '\Drupal\puzzlehunt\Controller\JeditableAjax::jeditableAjaxSave'
      );
    }
  }

}
