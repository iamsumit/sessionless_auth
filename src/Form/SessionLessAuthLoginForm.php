<?php

namespace Drupal\sessionless_auth\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\sessionless_auth\SessionLessAuthPluginManager;
use Drupal\sessionless_auth\SessionLessAuthSessionManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Displays form provided by session less auth plugins.
 */
class SessionLessAuthLoginForm extends FormBase {

  /**
   * The sessionless auth plugin manager.
   *
   * @var \Drupal\sessionless_auth\SessionLessAuthPluginManager
   */
  protected $sessionLessAuth;

  /**
   * The plugin definition of current.
   *
   * @var array
   */
  protected $pluginDefinition;

  /**
   * The plugin definition of current.
   *
   * @var \Drupal\sessionless_auth\SessionLessAuthInterface
   */
  protected $pluginInstance;

  /**
   * The sessionless auth session manager.
   *
   * @var \Drupal\sessionless_auth\SessionLessAuthSessionManager
   */
  protected $sessionManager;

  /**
   * Constructs a new VocabularyResetForm object.
   *
   * @param \Drupal\sessionless_auth\SessionLessAuthPluginManager $sessionless_auth
   *   The sessionless auth plugin manager.
   * @param \Drupal\sessionless_auth\SessionLessAuthSessionManager $session_manager
   *   The sessionless auth session manager.
   */
  public function __construct(SessionLessAuthPluginManager $sessionless_auth, SessionLessAuthSessionManager $session_manager) {
    $this->sessionLessAuth = $sessionless_auth;
    $this->pluginDefinition = $this->sessionLessAuth->getDefinitionByAnnotationKey('url', $this->getRouteMatch()->getRouteObject()->getPath());
    $this->pluginInstance = $this->sessionLessAuth->createInstance($this->pluginDefinition['id']);
    $this->sessionManager = $session_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.sessionless_auth'),
      $container->get('sessionless_auth.session_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'sessionless_auth_' . $this->pluginDefinition['id'] . '_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $filter = '') {
    $this->pluginInstance->loginForm($form, $form_state);
    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => t('Login'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $this->pluginInstance->validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $user_details = $this->pluginInstance->submitForm($form, $form_state);
    if (!empty($user_details)) {
      $this->sessionManager->createSession($this->pluginDefinition['id'], $user_details);
    }
    $this->pluginInstance->afterSubmit($form, $form_state);
  }

}
