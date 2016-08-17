<?php

/**
 * @file
 * sparql_endpoint.api.php
 *
 */

/**
 * Implements hook_sparql_endpoint_configurations_alter().
 *
 * Add, edit or remove defined SPARQL endpoint configurations.
 *
 * @param &$config
 *   The array to alter
 */
function sparql_endpoint_configurations_alter(&$config) {

}

/**
 * Implements hook_sparql_endpoint_pre_execute_alter().
 *
 * Provide mechanism for modules to react to a SPARQL statement.
 * Use Cases:
 *
 * 1) To provide username & password credentials so that they aren't stored in
 *    the SparqlEndpointConfig.
 *
 * @param \Drupal\sparql_endpoint\SparqlEndpointInterface &$endpoint
 *    The SparqlEndpoint
 *
 * @param string $statement
 *    The statement to execute
 *
 * @param array $options
 *    The array of statement options
 */
function sparql_endpoint_pre_execute_alter(\Drupal\sparql_endpoint\SparqlEndpointInterface &$endpoint, &$statement, array &$options) {
  watchdog(
    'sparql_endpoint',
    '@endpoint about to execute statement: @statement',
    array('@endpoint' => $endpoint->__toString(), '@statement' => $statement),
    WATCHDOG_NOTICE,
    $endpoint->getConfiguration()->getEndpointUrl(),
  );

  // Provide authentication information to an Authenticator.
  $options['credentials'] = array(
    'username' => 'admin',
    'password' => 'some-credentials-for-admin-account',
  );
}

/**
 * Implements hook_sparql_endpoint_statement_exception().
 *
 * Provide mechanism for modules to react to a SPARQL statement.
 *
 * @param Exception $e
 *   The caught Exception
 *
 * @param \Drupal\sparql_endpoint\SparqlEndpointInterface &$endpoint
 *    The SparqlEndpoint
 *
 * @param string $statement
 *    The statement to execute
 *
 * @param array $options
 *    The array of statement options
 */
function sparql_endpoint_statement_exception(Exception $e, \Drupal\sparql_endpoint\SparqlEndpointInterface $endpoint, $statement, $options) {
  if ($e instanceof \Drupal\sparql_endpoint\SparqlEndpointException) {
    watchdog('sparql_endpoint', $e->__toString(), array(), WATCHDOG_ERROR);
  }
  else {
    watchdog('sparql_endpoint', $e->getMessage(), array(), WATCHDOG_ERROR);
  }
}

/**
 * Implements hook_sparql_endpoint_post_execute_alter().
 *
 * Provide mechanism for modules to react to a SPARQL statement result.
 *
 * @param mixed $result
 *   The result of executing the SPARQL statement
 *
 * @param \Drupal\sparql_endpoint\SparqlEndpointInterface &$endpoint
 *   The SparqlEndpoint
 *
 * @param string $statement
 *   The statement to execute
 *
 * @param array $options
 *   The array of statement options
 */
function sparql_endpoint_post_execute_alter($result, \Drupal\sparql_endpoint\SparqlEndpointInterface &$endpoint, &$statement, array &$options) {
  watchdog(
    'sparql_endpoint',
    '@endpoint executed statement: @statement',
    array('@endpoint' => $endpoint->__toString(), '@statement' => $statement),
    WATCHDOG_NOTICE,
    $endpoint->getConfiguration()->getEndpointUrl(),
  );
}
