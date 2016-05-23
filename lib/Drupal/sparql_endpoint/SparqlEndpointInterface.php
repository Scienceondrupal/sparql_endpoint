<?php

/**
 * @file
 * SparqlEndpointInterface.php
 *
 */

namespace Drupal\sparql_endpoint;

use Drupal\sparql_endpoint\SparqlEndpointConfig;

interface SparqlEndpointInterface {

  public static function getInstance(array $config);
  public function getConfiguration();
  public function setConfiguration(SparqlEndpointConfig $config);
  public function __toString();
  public function executeStatement($statement, array $options = array());
}
