<?php

/**
 * @file
 * SparqlEndpointConfig.class.php
 *
 */

namespace Drupal\sparql_endpoint;

class SparqlEndpointConfig {

  private string $endpoint_url;
  private string $request_handler;

  public function __construct(array $config) {
    $this->endpoint_url = $config['endpoint_url'];
    $this->request_handler = isset($config['request_handler']) ? $config['request_handler'] : 'SparqlEndpointRequestHandler';
  }

  public function getEndpointUrl() {
    return $this->endpoint_url;
  }

  public function getRequestHandler() {
    return $this->request_handler;
  }

  public function __toString() {
    $str = 'Endpoint URL: ' . $this->$endpoint_url;
    return $str;
  }
}
