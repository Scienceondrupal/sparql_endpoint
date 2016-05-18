<?php

/**
 * @file
 * SparqlEndpointResponseParser.php
 *
 */

namespace Drupal\sparql_endpoint\parsers;

use Drupal\sparql_endpoint\SparqlEndpointConfig;

/**
 * Passes on the returned response.
 */
class SparqlEndpointResponseParser implements SparqlEndpointResponseParserInterface {

  /**
   * Process an HTTP request with drupal_http_request()
   *
   * @param SparqlEndpointConfig $config
   *    The configuration of the SPARQL endpoint
   *
   * @param mixed $response
   *    The result from a SparqlEndpointRequestHandler::handleRequest()
   *
   * @return mixed
   *   The parsed response
   */
  public static function parse(SparqlEndpointConfig $config, $response) {
    return $response;
  }
}
