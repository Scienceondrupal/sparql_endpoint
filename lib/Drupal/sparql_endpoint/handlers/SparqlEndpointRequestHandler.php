<?php

/**
 * @file
 * SparqlEndpointRequestHandler.php
 *
 */

namespace Drupal\sparql_endpoint\handlers;

use Drupal\sparql_endpoint\SparqlEndpointConfig;
use Drupal\sparql_endpoint\SparqlEndpointException;

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
   * @param array $options
   *    Options provided for overrides
   *
   * @return mixed
   *   The response data
   */
  public static function handleRequest(SparqlEndpointConfig $config, $url, $method, array $headers, $request_data, array $options) {

    // Prepare request options
    $req_options = array(
      'method' => $method,
      'headers' => $headers,
      'data' => $request_data,
    );

    // Execute a drupal_http_request().
    $response = drupal_http_request($url, $req_options);
    switch ($response->code) {
      case 200:
        return $response->data;

      case 401:
        if (empty($headers['Authorization'])) {
          return SparqlEndpointRequestHandler::authenticate($response, $config, $url, $method, $headers, $request_data, $options);
        }
        throw new SparqlEndpointException("Authentication failed.",401, $headers);

      default:
        $msg = (!empty($response->data)) ? $response->data : (!empty($response->error) ? $response->error : 'Unknown Error occurred');
        $headers = (!empty($response->headers)) ? $response->headers : array();
        throw new SparqlEndpointException($msg, $response->code, $headers);
    }

    return NULL;
  }

  /**
   * Process an HTTP request with drupal_http_request()
   *
   * @param object $response
   *    The drupal_http_request() response.
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
   * @param array $options
   *    Options provided for overrides
   *
   * @return mixed
   *   The response data
   *
   * @throws SparqlEndpointException
   */
  public static function authenticate($response, SparqlEndpointConfig $config, $url, $method, array $headers, $request_data, array $options) {

    $authenticator_class = $config->getAuthenticator();
    if (!class_exists($authenticator_class)) {
      throw new \Exception(t('Authenticator class "@class" does not exist', array('@class' => $authenticator_class)));
    }

    try {
      $auth_info = array(
        'uri' => parse_url($url),
        'www_auth_header' => $response->headers['www-authenticate'],
        'username' => isset($options['credentials']['username']) ? $options['credentials']['username'] : FALSE,
        'password' => isset($options['credentials']['password']) ? $options['credentials']['password'] : FALSE,
      );
      $headers['Authorization'] = $authenticator_class::authenticate($auth_info);
      return SparqlEndpointRequestHandler::handleRequest($config, $url, $method, $headers, $request_data, $options);
    }
    catch (\Exception $e) {
      throw new SparqlEndpointException($e->getMessage(), 401, $headers);
    }
  }
}
