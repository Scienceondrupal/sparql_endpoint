<?php

/**
 * @file
 *
 * SparqlEndpointException.php
 */

namespace Drupal\sparql_endpoint;

class SparqlEndpointException extends \Exception {

  protected $headers = array();
  protected $message = '';

  /**
   * List of HTTP status codes
   *
   * From http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
   *
   * @var array
   */
  private $status = array(
      400 => 'Bad Request',
      401 => 'Unauthorized',
      402 => 'Payment Required',
      403 => 'Forbidden',
      404 => 'Not Found',
      405 => 'Method Not Allowed',
      406 => 'Not Acceptable',
      407 => 'Proxy Authentication Required',
      408 => 'Request Timeout',
      409 => 'Conflict',
      410 => 'Gone',
      411 => 'Length Required',
      412 => 'Precondition Failed',
      413 => 'Request Entity Too Large',
      414 => 'Request-URI Too Long',
      415 => 'Unsupported Media Type',
      416 => 'Requested Range Not Satisfiable',
      417 => 'Expectation Failed',
      418 => 'I\'m a teapot', // RFC 2324
      419 => 'Authentication Timeout', // not in RFC 2616
      420 => 'Method Failure', // Spring Framework
      420 => 'Enhance Your Calm', // Twitter
      422 => 'Unprocessable Entity', // WebDAV; RFC 4918
      423 => 'Locked', // WebDAV; RFC 4918
      424 => 'Failed Dependency', // WebDAV; RFC 4918
      424 => 'Method Failure', // WebDAV)
      425 => 'Unordered Collection', // Internet draft
      426 => 'Upgrade Required', // RFC 2817
      428 => 'Precondition Required', // RFC 6585
      429 => 'Too Many Requests', // RFC 6585
      431 => 'Request Header Fields Too Large', // RFC 6585
      444 => 'No Response', // Nginx
      449 => 'Retry With', // Microsoft
      450 => 'Blocked by Windows Parental Controls', // Microsoft
      451 => 'Unavailable For Legal Reasons', // Internet draft
      451 => 'Redirect', // Microsoft
      494 => 'Request Header Too Large', // Nginx
      495 => 'Cert Error', // Nginx
      496 => 'No Cert', // Nginx
      497 => 'HTTP to HTTPS', // Nginx
      499 => 'Client Closed Request', // Nginx
      500 => 'Internal Server Error',
      501 => 'Not Implemented',
      502 => 'Bad Gateway',
      503 => 'Service Unavailable',
      504 => 'Gateway Timeout',
      505 => 'HTTP Version Not Supported',
      506 => 'Variant Also Negotiates', // RFC 2295
      507 => 'Insufficient Storage', // WebDAV; RFC 4918
      508 => 'Loop Detected', // WebDAV; RFC 5842
      509 => 'Bandwidth Limit Exceeded', // Apache bw/limited extension
      510 => 'Not Extended', // RFC 2774
      511 => 'Network Authentication Required', // RFC 6585
      598 => 'Network read timeout error', // Unknown
      599 => 'Network connect timeout error', // Unknown
  );

  /**
   * @param string $message
   *    The Exception message
   *
   * @param int $code
   *    Defaults to 500
   *
   * @param array $headers
   *    List of additional headers
   */
  public function __construct($message, $code = 500, array $headers = array()) {
    parent::__construct($message, $code);
    $phrase = !empty($this->status[$code]) ? $this->status[$code] : t('Unknown Error Code: @code', array('@code' => $code));
    $header = sprintf('HTTP/1.1 %d %s', $code, $phrase);
    $this->addHeader($header)->addHeaders($headers);
  }

  /**
   * Returns the list of additional headers
   *
   * @return array
   */
  public function getHeaders() {
    return $this->headers;
  }

  /**
   * @param string $header
   *
   * @return self
   */
  public function addHeader($header) {
    $this->headers[] = $header;
    return $this;
  }

  /**
   * @param array $headers
   *
   * @return self
   */
  public function addHeaders(array $headers) {
    foreach ($headers as $key => $header) {
      if (!is_int($key)) {
        $header = $key . ': ' . $header;
      }
      $this->addHeader($header);
    }
    return $this;
  }

  /**
   * Define a message.
   *
   * @param string $msg
   *
   * @return self
   */
  public function setMessage($msg) {
      $this->message = (string) $msg;
      return $this;
  }

  public function __toString() {
    return $this->asString();
  }

  public function asString($delimiter = "\n") {
    $arr = array(
      $this->getMessage(),
    );

    if (!empty($this->headers)) {
      $arr = array_merge($arr, $this->headers);
    }

    return implode($delimiter, $arr);
  }

}
