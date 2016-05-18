<?php

/**
 * @file
 * SparqlEndpoint.php
 *
 */

namespace Drupal\sparql_endpoint;

use Drupal\sparql_endpoint\SparqlEndpointInterface;
use Drupal\sparql_endpoint\SparqlEndpointConfig;
use Drupal\sparql_endpoint\SparqlEndpointException;
use Drupal\sparql_endpoint\handlers\SparqlEndpointRequestHandlerInterface;
use Drupal\sparql_endpoint\handlers\SparqlEndpointRequestHandler;
use Drupal\sparql_endpoint\parsers\SparqlEndpointResponseParserInterface;
use Drupal\sparql_endpoint\parsers\SparqlEndpointResponseParser;
use Drupal\sparql_endpoint\parsers\SparqlEndpointResponseSparqlJsonParser;

class SparqlEndpoint implements SparqlEndpointInterface {

  /**
   * @var SparqlEndpoint
   *   The reference to *SparqlEndpoint* instance of this class.
   */
  private static $instance;

  /**
   * @var SparqlEndpointConfig
   *   The configuration for this SparqlEndpoint.
   */
  private $config;

  public function __construct(SparqlEndpointConfig $config) {
    $this->setConfiguration($config);
  }

  /**
   * Returns the *SparqlEndpoint* instance of this class.
   *
   * @param SparqlEndpointConfig $config
   *    The SparqlEndpointConfig for this SparqlEndpoint.
   *
   * @return SparqlEndpoint
   *   The *SparqlEndpoint* instance.
   */
  public static function getInstance(array $config) {
    $config = new SparqlEndpointConfig($config);
    if (NULL === static::$instance) {
        static::$instance = new static($config);
    }

    return static::$instance;
  }

  public function setConfiguration(SparqlEndpointConfig $config) {
    $this->config = $config;
  }

  public function getConfiguration(){
    return $this->config;
  }

  public function __toString() {
    return $this->getConfiguration()->__toString();
  }

  /**
   * Execute a SPARQL query.
   *
   * @param string $query
   *   The SPARQL query to run
   *
   * @param array $options
   *   Options for altering the default request
   *
   * @return mixed
   *
   * @throws SparqlEndpointException from handleResponse()
   */
  public function executeQuery($query, array $options = array()) {

    $url = $this->getConfiguration()->getEndpointUrl();

    // Allow the caller to override the handler & parser.
    $request_handler = isset($options['request_handler']) ? $options['request_handler'] : $this->getConfiguration()->getRequestHandler();
    $parser = isset($options['parser']) ? $options['parser'] : $this->getConfiguration()->getResponseParser();

    $cfg_options = $this->getConfiguration()->getOptions();
    $options += $cfg_options;

    // HTTP Method.
    $method = isset($options['method']) ? $options['method'] : 'POST';

    // Request Headers.
    $headers = array();
    if ('POST' == $method) {
      $headers['Content-Type'] = 'application/x-www-form-urlencoded';
    }
    // Check for user-defined headers.
    if (!empty($options['headers'])) {
      foreach ($options['headers'] as $header => $value) {
        $headers[$header] = $value;
      }
    }
    // Query String.
    $data = 'query=' . urlencode($query);
    // Check for user-defined query string extras.
    if (!empty($options['query_string'])) {
      foreach ($options['query_string'] as $key => $value) {
        $data .= '&' . $key . '=' . urlencode($value);
      }
    }

    // Handle the request.
    $response = $request_handler::handleRequest($this->getConfiguration(), $url, $method, $headers, $data);

    // Return the parsed response.
    return $parser::parse($this->getConfiguration(), $response);
  }

  /**
   * Implements loadData().
   */
  public function loadData($data, array $options = array()) {
    throw new Exception('Not implemented');
  }

  /**
   * Implements deleteData().
   */
  public function deleteData($data, array $options = array()) {
    throw new Exception('Not implemented');
  }

  /**
   * Implements clearGraph().
   */
  public function clearGraph($graph, array $options = array()) {
    throw new Exception('Not implemented');
  }
}
