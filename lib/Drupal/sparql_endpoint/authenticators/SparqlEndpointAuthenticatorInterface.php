<?php

/**
 * @file
 * SparqlEndpointAuthenticatorInterface.php
 *
 */

namespace Drupal\sparql_endpoint\authenticators;

use \Drupal\sparql_endpoint\SparqlEndpointConfig;

interface SparqlEndpointAuthenticatorInterface {

  public static function authenticate(array $data);
}
