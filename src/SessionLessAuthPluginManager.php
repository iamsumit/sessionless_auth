<?php

/**
 * @file
 * Contains SessionLessAuthPluginManager.
 */

namespace Drupal\sessionless_auth;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Symfony\Component\Routing\Route;

/**
 * Session less auth plugin manager.
 */
class SessionLessAuthPluginManager extends DefaultPluginManager {

  /**
   * Constructs an SessionLessAuthPluginManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations,
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/SessionLessAuth', $namespaces, $module_handler, 'Drupal\sessionless_auth\SessionLessAuthInterface', 'Drupal\sessionless_auth\Annotation\SessionLessAuth');

    $this->alterInfo('sessionless_auth_info');
    $this->setCacheBackend($cache_backend, 'sessionless_auth');
  }

  /**
   * Gets a specific plugin definition.
   *
   * @return mixed[]
   *   A plugin definition, or NULL if the key is invalid.
   */
  public function getDefinitionByAnnotationKey($key, $value) {
    $definitions = $this->getDefinitions();
    if (!empty($definitions)) {
      foreach ($definitions as $definition) {
        if ($definition[$key] == $value) {
          return $definition;
        }
      }
    }
    return NULL;
  }

  /**
   * Returns all the routes specified by session less auth plugin.
   *
   * @return array
   */
  public function getRoutesOfPlugins() {
    $routes = [];
    $definitions = $this->getDefinitions();
    foreach ($definitions as $plugin) {
      $routes['sessionless_auth.' . $plugin['id']] = new Route(
        $plugin['url'],
        array(
          '_form' => '\Drupal\sessionless_auth\Form\SessionLessAuthLoginForm',
          '_title' => (string) $plugin['title']
        ),
        array(
          '_permission'  => 'access content',
        )
      );
    }
    return $routes;
  }

}