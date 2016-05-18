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
  public function executeQuery($query, array $options = array());
  public function loadData($data, array $options = array());
  public function deleteData($data, array $options = array());
  public function clearGraph($graph, array $options = array());
}
