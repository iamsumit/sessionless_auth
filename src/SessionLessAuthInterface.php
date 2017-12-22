<?php

/**
 * @file
 * Provides Drupal\sessionless_auth\SessionLessAuthInterface
 */

namespace Drupal\sessionless_auth;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines an interface for session less auth plugins.
 */
interface SessionLessAuthInterface extends PluginInspectionInterface {

  /**
   * Create form array.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function loginForm(array &$form, FormStateInterface $form_state);

  /**
   * Validation handler for form array.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state);

  /**
   * Submit handler for form array.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return mixed
   *   Return data to store securely in session.
   */
  public function submitForm(array &$form, FormStateInterface $form_state);

  /**
   * Do something once form is submitted successfully and session is created.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function afterSubmit(array &$form, FormStateInterface $form_state);

}
