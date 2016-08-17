<?php

/**
 * @file
 * SparqlEndpointHTTPDigestAuthenticator.php
 *
 */

namespace Drupal\sparql_endpoint\authenticators;

use \Drupal\sparql_endpoint\SparqlEndpointConfig;
use \Drupal\sparql_endpoint\SparqlEndpointException;

class SparqlEndpointDigestAuthenticator implements SparqlEndpointAuthenticatorInterface {

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

    $uri = !empty($data['uri']['path']) ? $data['uri']['path'] : FALSE;
    $digest_header = $data['www_auth_header'];
    $username = $data['username'];
    $password = $data['password'];
    if (isset($uri) && !empty($digest_header) && !empty($username) && isset($password)) {
      // DIGEST Auth.
      $value = explode(' ', $digest_header, 2);
      $data = array();
      $parts = explode(", ", $value[1]);
      foreach ($parts as $element) {
        $bits = explode("=", $element);
        $data[$bits[0]] = str_replace('"', '', $bits[1]);
      }

      if ($data['qop'] == 'auth') {
        $cnonce = time();
        $ncvalue = '00000001';
        $noncebit = $data['nonce'] . ":" . $ncvalue . ":" . $cnonce . ":auth:" . md5("POST:" . $uri);
        $a = md5($username . ":" . $data['realm'] . ":" . $password);
        $respdig = md5("$a:$noncebit");
        $auth_header = 'Digest username="' . $username . '", realm="' . $data['realm'] . '", nonce="' . $data['nonce'] . '",';
        $auth_header .= ' uri="' . $uri . '", algorithm=' . $data['algorithm'] . ', response="' . $respdig . '", opaque="' . $data['opaque'];
        $auth_header .= '", qop=' . $data['qop'] . ', nc=' . $ncvalue . ', cnonce="' . $cnonce . '"';
        return $auth_header;
      } else {
        throw new \Exception("Could not authenticate the quality of protection value " . $data['qop'] . " for $uri");
      }
    }

    throw new \Exception("Could not find the necessary credentials to authenticate request to $uri");
  }
}
