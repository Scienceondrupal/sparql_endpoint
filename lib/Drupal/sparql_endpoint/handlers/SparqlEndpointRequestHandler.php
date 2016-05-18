<?php

/**
 * @file
 * SparqlEndpointRequestHandler.php
 *
 */

namespace Drupal\sparql_endpoint\handlers;

use Drupal\sparql_endpoint\SparqlEndpointConfig;

/**
 * Uses drupal_http_request().
 */
class SparqlEndpointRequestHandler implements SparqlEndpointRequestHandlerInterface {

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
   *   The data of the drupal_http_request()
   *
   * @throws SparqlEndpointException if response code != 200
   */
  public static function handleRequest(SparqlEndpointConfig $config, $url, $method, array $headers, $request_data) {
    // Prepare request options
    $req_options = array(
      'method' => $method,
      'headers' => $headers,
      'data' => $request_data,
    );

    // Execute a drupal_http_request().
    $response = drupal_http_request($url, $req_options);
    if (200 == $response->code) {
      return $response->data;
    }

    $msg = (!empty($response->data)) ? $response->data : 'Unknown Error occurred';
    $headers = (!empty($response->headers)) ? $response->headers : array();
    throw new SparqlEndpointException($msg, $response->code, $headers);
  }
}
