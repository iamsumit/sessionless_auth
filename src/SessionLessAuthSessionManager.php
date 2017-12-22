<?php

/**
 * @file
 * Contains SessionLessAuthSessionManager.
 */

namespace Drupal\sessionless_auth;

use Drupal\Core\Site\Settings;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Session less auth session manager.
 */
class SessionLessAuthSessionManager {

  /**
   * The session.
   *
   * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
   */
  protected $session;

  /**
   * Constructs an SessionLessAuthSessionManager object.
   *
   * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
   *   The session.
   */
  public function __construct(SessionInterface $session) {
    $this->session = $session;
  }

  /**
   * Private function to encrypt data.
   *
   * @param string $string
   *   String to encode.
   *
   * @return string
   *   Encrypted data.
   */
  private function encrypt($string, $plugin_id) {
    // Create encrypted key using plugin id and from settings.
    $encryption_key = (Settings::get('SESSIONLESS_AUTH_SECURE_KEY')) ? $plugin_id . Settings::get('SESSIONLESS_AUTH_SECURE_KEY') : $plugin_id;
    // Create a key using a string data.
    $key = openssl_digest($encryption_key, 'sha256');
    // Create an intialization vector to be used for encryption.
    $iv = openssl_random_pseudo_bytes(16);
    // Encrypt string data along with intilization vector so that intialization
    // vector can be used for decryption of this string.
    $encrypted = openssl_encrypt($iv . $string, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    // Add a signature to string.
    $signature = hash_hmac('sha256', $encrypted, $key);
    // Encode signature and string.
    return base64_encode($signature . $encrypted);
  }

  /**
   * Private function to decrypt data.
   *
   * @param string $string
   *   Encrypted string.
   *
   * @return bool|mixed
   *   False if retrieved signature doesn't matches
   *   or data.
   */
  private function decrypt($string, $plugin_id) {
    $encryption_key = (Settings::get('SESSIONLESS_AUTH_SECURE_KEY')) ? $plugin_id . Settings::get('SESSIONLESS_AUTH_SECURE_KEY') : $plugin_id;
    // Create a key using a string data used while encryption.
    $key = openssl_digest($encryption_key, 'sha256');
    // Reverse base64 encryption of $string.
    $string = base64_decode($string);
    // Extract signature from string.
    $signature = substr($string, 0, 64);
    // Extract data without signature.
    $encryptedData = substr($string, 64);
    // Signature should match for verification of data.
    if ($signature !== hash_hmac('sha256', $encryptedData, $key)) {
      return FALSE;
    }
    // Extract initialization vector from data appended while encryption.
    $iv = substr($string, 64, 16);
    // Extract main encrypted string data which contains profile details.
    $encrypted = substr($string, 80);
    // Decrypt the data using key and
    // intialization vector extracted above.
    return openssl_decrypt($encrypted, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
  }

  /**
   * Create a secure anonymous session.
   *
   * @param string $plugin_id
   *   Plugin id for which session needs to create.
   * @param mixed $data
   *   Data to store in cookie.
   */
  public function createSession($plugin_id, $data) {
    $data = (is_array($data)) ? json_encode($data) : $data;
    $data = base64_encode($this->encrypt($data, $plugin_id));
    $this->session->set($plugin_id, $data);
  }

  /**
   * Get a session.
   */
  public function getSession($plugin_id) {
    $session = base64_decode($this->session->get($plugin_id));
    if ($session) {
      $decrypt = $this->decrypt($session, $plugin_id);
      return (json_decode($decrypt, TRUE)) ?: $decrypt;
    }
    return NULL;
  }

  /**
   * Delete a session.
   */
  public function deleteSession() {
    $this->session->invalidate();
  }

}