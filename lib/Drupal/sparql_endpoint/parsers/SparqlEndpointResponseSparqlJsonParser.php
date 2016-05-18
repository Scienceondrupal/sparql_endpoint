<?php

/**
 * @file
 * SparqlEndpointResponseSparqlJsonParser.php
 *
 */

namespace Drupal\sparql_endpoint\parsers;

use Drupal\sparql_endpoint\SparqlEndpointConfig;

/**
 * Passes on the returned response.
 */
class SparqlEndpointResponseSparqlJsonParser implements SparqlEndpointResponseParserInterface {

  /**
   * Process an HTTP request with drupal_http_request()
   *
   * @param SparqlEndpointConfig $config
   *    The configuration of the SPARQL endpoint
   *
   * @param mixed $response
   *    The result from a SparqlEndpointRequestHandler::handleRequest()
   *
   * @return array
   *   The reponse data as an associative array
   */
  public static function parse(SparqlEndpointConfig $config, $response) {
    $json = drupal_json_decode($response);
    $data = array();
    if( !empty($json['head']['vars']) && !empty($json['results']['bindings']) ) {
      $data = array();
      foreach($json['results']['bindings'] as $idx => $row) {
        foreach($json['head']['vars'] as $var) {
          $data[$idx][$var] = isset($json['results']['bindings'][$idx][$var]['value']) ? $json['results']['bindings'][$idx][$var]['value'] : '';
        }
      }
    }
    return $data;
  }
}
