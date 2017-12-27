<?php

/**
 * @file
 * Contains \Drupal\sessionless_auth\Plugin\Flavor\Vanilla.
 */

namespace Drupal\sessionless_auth\Plugin\SessionLessAuth;

use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'drupal' authentication.
 *
 * @SessionLessAuth(
 *   id = "drupal_auth",
 *   title = @Translation("User Login"),
 *   url = "/drupal-login"
 * )
 */
class DrupalAuth extends SessionLessAuthBase {

  /**
   * {@inheritdoc}
   */
  public function loginForm(array &$form, FormStateInterface $form_state) {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => t('User Name'),
      '#maxlength' => 25,
      '#required' => TRUE,
    ];
    $form['password'] = [
      '#type' => 'password',
      '#title' => t('Password'),
      '#maxlength' => 25,
      '#required' => TRUE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Authenticate credentials here and return the data to store in cookie.
    return [
      'name' => 'test',
      'email' => 'test@testing.com',
      'uid' => 12132132
    ];
  }

}
