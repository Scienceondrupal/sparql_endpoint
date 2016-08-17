<?php

/**
 * @file
 * SparqlEndpointConfig.php
 *
 */

namespace Drupal\sparql_endpoint;

/**
 * OPTIONS => array(
 *   'method' => The HTTP method to use for requests.
 *   'headers' => THe HTTP Request headers to set.
 *   'query_string' => An array of key-value pairs to append to the request query string
 *   'credentials' => array(
 *     'username' => The username to use to authenticate,
 *     'password' => The authentcation password for the username,
 *   ),
 * )
 */
class SparqlEndpointConfig {

  private $endpoint_url;
  private $request_handler;
  private $response_parser;
  private $query_string;
  private $authenticator;
  private $options;

  public function __construct(array $config) {
    $this->endpoint_url = $config['endpoint_url'];
    $this->request_handler = isset($config['request_handler']) ? $config['request_handler'] : '\Drupal\sparql_endpoint\handlers\SparqlEndpointRequestHandler';
    $this->response_parser = isset($config['response_parser']) ? $config['response_parser'] : '\Drupal\sparql_endpoint\parsers\SparqlEndpointResponseParser';
    $this->authenticator = isset($config['authenticator']) ? $config['authenticator'] : '\Drupal\sparql_endpoint\authenticator\SparqlEndpointHttpAuthenticator';
    $this->options = isset($config['options']) ? $config['options'] : array();
  }

  public function getEndpointUrl() {
    return $this->endpoint_url;
  }

  public function getRequestHandler() {
    return $this->request_handler;
  }

  public function getResponseParser() {
    return $this->response_parser;
  }

  public function getAuthenticator() {
    return $this->authenticator;
  }

  public function getOptions() {
    return $this->options;
  }

  public function __toString() {
    $newline = "\n";
    $indent = '  ';
    $str = array();
    // Endpoint URL.
    $str[] = 'Endpoint URL: ' . $this->getEndpointUrl();

    // Request Handler.
    $str[] = 'Request Handler: ' . $this->getRequestHandler();

    // Response Parser.
    $str[] = 'Response Parser: ' . $this->getResponseParser();

    // Authenticator.
    $str[] = 'Authenticator: ' . $this->getAuthenticator();

    // Options.
    if (!empty($this->options)) {
      $str[] = 'Options: ' . $this->_printArrayForToString($this->options, $indent);
    }

    $retval = implode($newline, $str);
    return htmlspecialchars($retval, ENT_QUOTES, 'UTF-8');
  }

  private function _printArrayForToString($arr, $indent_string, $level = 1) {
    $newline = "\n";
    $indent = '';
    for ($a = 0; $a < $level; $a++) {
      $indent .= $indent_string;
    }

    $print = array();
    foreach ($arr as $key => $value) {
      $val = is_array($value) ? $this->_printArrayForToString($value, $indent_string, $level+1) : strval($value);
      $print[] = $key . ': ' . $val;
    }
    return $newline . $indent . implode($newline . $indent, $print);
  }
}
