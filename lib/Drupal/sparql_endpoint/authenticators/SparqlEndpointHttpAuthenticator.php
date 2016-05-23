<?php

/**
 * @file
 * SparqlEndpointHTTPAuthenticator.php
 *
 */

namespace Drupal\sparql_endpoint\authenticators;

use \Drupal\sparql_endpoint\SparqlEndpointConfig;
use \Drupal\sparql_endpoint\SparqlEndpointException;

class SparqlEndpointHttpAuthenticator implements SparqlEndpointAuthenticatorInterface {

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
    if (!isset($data['www_auth_header'])) {
      throw new Exception('Could not parse the WWW-Authenticate header');
    }

    $tokens = explode(' ', strtolower($data['www_auth_header']));
    if (!empty($tokens[0])) {
      switch ($tokens[0]) {
        case 'basic':
          return SparqlEndpointHTTPBasicAuthenticator::authenticate($data);

        case 'digest':
          return SparqlEndpointHTTPDigestAuthenticator::authenticate($data);
      }

      throw new \Exception("No handler defined for '$tokens[0]'");
    }

    throw new \Exception("Could not determine how to authenticate for $data['www_auth_header']");
  }
}
