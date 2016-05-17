<?php

/**
 * @file
 * SparqlEndpoint.class.php
 *
 */


namespace Drupal\sparql_endpoint;

interface SparqlEndpointInterface {

  public function init(SparqlEndpointConfig $config);
  public function getConfiguration();
  protected function setConfiguration(SparqlEndpointConfig $config)
  public function __toString();
  public function executeQuery(string $query, array $options = array());
  public function loadData($data, array $options = array());
  public function deleteData($data, array $options = array());
  public function clearGraph(string $graph, array $options = array());
}

class SparqlEndpoint implements SparqlEndpointInterface {

  private SparqlEndpointConfig $config = NULL;

  public function init(SparqlEndpointConfig $config) {
    setConfiguration($config);
  }

  protected function setConfiguration(SparqlEndpointConfig $config) {
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
   *   THe SPARQL query to run
   *
   * @param array $options
   *   Options for altering the default request
   *
   * @return mixed
   *
   * @throws SparqlEndpointException from handleResponse()
   */
  public function executeQuery(string $query, array $options = array()) {

    $url = $this->getConfiguration()->getEndpointUrl();
    $request_handler = $this->getConfiguration()->getRequestHandler();

    $method = !empty($options['method']) ? $options['method'] : 'POST';

    $headers['Content-Type'] = 'application/x-www-form-urlencoded';
    if (!empty($options['headers'])) {
      foreach ($options['headers'] as $header => $value) {
        $headers[$header] = $value;
      }
    }

    $data = 'query=' . urlencode($query);
    if (!empty($options['query_string'])) {
      foreach ($options['query_string'] as $key => $value) {
        $data .= '&' . $key . '=' . $value;
      }
    }

    return $request_handler::handleRequest($this->getConfiguration(), $url, $method, $headers, $data);
  }

  public function loadData($data, array $options = array()) {
    throw new Exception('Not implemented');
  }

  public function deleteData($data, array $options = array()) {
    throw new Exception('Not implemented');
  }

  public function clearGraph(string $graph, array $options = array()) {
    throw new Exception('Not implemented');
  }
}
