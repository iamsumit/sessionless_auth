Introduction
====
There are many times where we use third party service for user authentication and where we don't need to store users in database. So we need to store some data like user id and access token in session/cookie to differentiate user.

But these data are very sensitive and we need a secure way to store this type of data in cookie. So we have used [openssl_encrypt](http://php.net/manual/en/function.openssl-encrypt.php) to encrypt the data before storing it in cookie. A unique encryption key is required to secure and the key should be combination of a setting "SESSIONLESS_AUTH_SECURE_KEY" in your settings.php and your plugin id.

    $settings['SESSIONLESS_AUTH_SECURE_KEY'] = 'secure key goes here.';

We are using drupal session service to create session, so drupal default session expiration will be used. You can change this in settings.php.  

## Drupal Plugin for SessionLessAuth
This module provides a basic plugin where you have to implement SessionLessAuthInterface. Annotation and methods provided by plugins are below:
 
    @SessionLessAuth(
      id = "drupal_auth",
      title = @Translation("User Login"),
      url = "/drupal-login"
    )

#####Annotation have three parts:

- id: Unique plugin id.
- title: Title of the login page.
- url: Path where login form should appear.

##### Methods provided by SessionLessAuth plugin:

- loginForm: You create your form elements which are required to authenticate a user for your third party service. You don't need to create a submit button, it is provided by plugin manager itself. 
- validateForm: You validate your form data here. $form_state will be transferred to submitForm in case you want to set some values in validate handler. 
- submitForm: You return data which you want to store in session. 
- afterSubmit: Once the data is saved in session you can perform some after login tasks like redirecting user.  


## Drupal Service for SessionLessAuth
Plugin is not enough if we need some other authentication method like social widgets or login form in block. So this module provides a service to create, get and invalidate session. Use "sessionless_auth.session_manager" service to manage secure session. Methods provided by the service are:

- createSession: You pass your plugin id and data as arguments to store in session.
- getSession: You pass your plugin id as argument to get the stored data from session.
- deleteSession: Delete the session for current user.

##### See <code>src/Plugin/SessionLessAuth/DrupalAuth.php</code> for reference.
