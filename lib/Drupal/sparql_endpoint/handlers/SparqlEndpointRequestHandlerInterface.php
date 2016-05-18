<?php

/**
 * @file
 * SparqlEndpointRequestHandlerInterface.php
 *
 */

namespace Drupal\sparql_endpoint\handlers;

use Drupal\sparql_endpoint\SparqlEndpointConfig;

interface SparqlEndpointRequestHandlerInterface {
  /**
   * Process an HTTP request with drupal_http_request()
   *
   * @param SparqlEndpointConfig $config
   *    The configuration of the SPARQL endpoint
   *
   * @param string $url
   *    The URL to request
   *
   * @param string $method
   *    The HTTP METHOD to use
   *
   * @param array $headers
   *    The HTTP request headers to set
   *
   * @param mixed $request_data
   *    Any data to include in the request
   *
   * @return mixed
   *   The response data
   */
  public static function handleRequest(SparqlEndpointConfig $config, $url, $method, array $headers, $request_data);
}
