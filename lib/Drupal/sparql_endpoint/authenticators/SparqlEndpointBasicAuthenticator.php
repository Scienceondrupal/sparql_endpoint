<?php

/**
 * @file
 * SparqlEndpointHTTPBasicAuthenticator.php
 *
 */

namespace Drupal\sparql_endpoint\authenticators;

use \Drupal\sparql_endpoint\SparqlEndpointConfig;
use \Drupal\sparql_endpoint\SparqlEndpointException;

class SparqlEndpointBasicAuthenticator implements SparqlEndpointAuthenticatorInterface {

  /**
   * Authenticate a DIGEST Auth response.
   *
   * @param array $data
   *   The necessary data to authenticate.
   *   'uri' => The URI to auth
   *   'www_auth_header' => The value of the 'WWW-Authenticate' response header
   *   'username' => The username
   *   'password' => The password for the username
   *
   * @return mixed
   *   Either FALSE or the value of the 'Authorization' header for the responding request
   *
   * @throws Exception
   */
  public static function authenticate(array $data) {

    $uri = $data['uri']['path'];

    if (!empty($data['username']) && isset($data['password'])) {
      return base64_encode($data['username'] . ':' . $data['password']);
    }

    throw new \Exception("Could not find the necessary credentials to authenticate request to $uri");
  }
}
