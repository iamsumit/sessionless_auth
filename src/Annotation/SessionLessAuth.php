<?php

/**
 * @file
 * Contains \Drupal\sessionless_auth\Annotation\SessionLessAuth.
 */

namespace Drupal\sessionless_auth\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a sessionless auth annotation object.
 *
 * Plugin Namespace: Plugin\sessionless_auth\SessionLessAuth
 *
 * @see \Drupal\sessionless_auth\Plugin\SessionLessAuth
 * @see plugin_api
 *
 * @Annotation
 */
class SessionLessAuth extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The title of the login page.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $title;

  /**
   * The url where login form should display.
   *
   * @var string
   */
  public $url;

}