<?php

/**
 * @file
 * SparqlEndpointConfig.php
 *
 */

namespace Drupal\sparql_endpoint;

class SparqlEndpointConfig {

  private $endpoint_url;
  private $request_handler;
  private $response_parser;
  private $query_string;

  public function __construct(array $config) {
    $this->endpoint_url = $config['endpoint_url'];
    $this->request_handler = isset($config['request_handler']) ? $config['request_handler'] : '\Drupal\sparql_endpoint\handlers\SparqlEndpointRequestHandler';
    $this->response_parser = isset($config['response_parser']) ? $config['response_parser'] : '\Drupal\sparql_endpoint\parsers\SparqlEndpointResponseParser';
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

  public function getOptions() {
    return $this->options;
  }

  public function __toString() {
    $newline = "\n";
    $indent = '  ';
    // Endpoint URL.
    $str[] = 'Endpoint URL: ' . $this->getEndpointUrl();

    // Request Handler.
    $str[] = 'Request Handler: ' . $this->getRequestHandler();

    // Response Parser.
    $str[] = 'Response Parser: ' . $this->getResponseParser();

    // Options.
    if (!empty($this->options)) {
      $str[] = 'Options: ' . $newline;
      $options = [];
      foreach ($this->options as $key => $value) {
        $str[] = $key . ': ' . _printArrayForToString($value, $indent);
      }
      $str[] = implode($newline . $indent, $options);
    }

    $retval = implode($newline, $str);
    return htmlspecialchars($retval, ENT_QUOTES, 'UTF-8');
  }

  private function _printArrayForToString($arr, $indent_string, $level = 1) {
    $indent = '';
    for ($a = 0; $a < $level; $a++) {
      $indent .= $indent_string;
    }
    $print = [];
    foreach ($arr as $key => $value) {
      $val = is_array($value) ? _printArrayForToString($value, $level+1) : strval($value);
      $print[] = $key . ': ' . $val;
    }
    return implode("\n" . $indent, $print);
  }
}