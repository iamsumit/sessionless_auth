<?php

/**
 * @file
 * Contains \Drupal\sessionless_auth.
 */

namespace Drupal\sessionless_auth\Plugin\SessionLessAuth;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Plugin\PluginBase;
use Drupal\sessionless_auth\SessionLessAuthInterface;

/**
 * Provides a 'base' class for sessionless auth plugin.
 * )
 */
class SessionLessAuthBase extends PluginBase implements SessionLessAuthInterface {

  /**
   * {@inheritdoc}
   */
  public function loginForm(array &$form, FormStateInterface $form_state) {}

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {}

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function afterSubmit(array &$form, FormStateInterface $form_state) {}

}