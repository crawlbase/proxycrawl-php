<?php
/**
 * A PHP class that acts as wrapper for ProxyCrawl API.
 *
 * Read ProxyCrawl API documentation https://proxycrawl.com/dashboard/docs
 *
 * Copyright ProxyCrawl
 * Licensed under the Apache License 2.0
 */
class ProxyCrawlAPI {

  const PUBLIC_PROXYCRAWL_API_URL = 'https://api.proxycrawl.com/';

  public $timeout = 90;
  public $debug = false;
  public $advDebug = false; // Note that enabling advanced debug will include debugging information in the response possibly breaking up your code

  private $response;
  private $endPointUrl;

  public function __construct($options = []) {
    if (empty($options['token'])) {
      throw new Exception('You need to specify the token');
    }

    $apiBaseUrl = isset($options['apiBaseUrl']) ? $options['apiBaseUrl'] : static::PUBLIC_PROXYCRAWL_API_URL;
    $this->options = $options;
    $this->endPointUrl = $apiBaseUrl . '?token=' . $options['token'];
  }

  public function get($url, array $options = []) {
    $options['method'] = 'GET';
    return $this->request($url, null, $options);
  }

  public function post($url, $data, array $options = []) {
    if (!isset($options['method'])) {
      $options['method'] = 'POST';
    }
    if (is_array($data)) {
      $data = http_build_query($data);
    }
    return $this->request($url, $data, $options);
  }

  public function put($url, $data, array $options = []) {
    $options['method'] = 'PUT';
    return $this->post($url, $data, $options);
  }

  private function request($url, $data = null, array $options = []) {
    if (!is_string($url)) {
      return trigger_error('Url must be a string', E_USER_ERROR);
    }
    $this->response = [];
    $this->response['headers'] = [];
    $url = $this->buildURL($url, $options);
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Don't print the result
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
    curl_setopt($curl, CURLOPT_FAILONERROR, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true); // Verify SSL connection
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); //         ""           ""
    curl_setopt($curl, CURLOPT_HEADERFUNCTION, [&$this, 'processResponseHeaders']);

    if ($this->advDebug) {
      curl_setopt($curl, CURLOPT_HEADER, true); // Display headers
      curl_setopt($curl, CURLINFO_HEADER_OUT, true); // Display output headers
      curl_setopt($curl, CURLOPT_VERBOSE, true); // Display communication with server
    }

    if ($options['method'] === 'POST') {
      curl_setopt($curl, CURLOPT_POST, true);
    } else if ($options['method'] === 'PUT') {
      curl_setopt($curl, CURLOPT_PUT, true);
    }
    if (!is_null($data) && ($options['method'] === 'POST' || $options['method'] === 'PUT')) {
      curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    try {
      $this->response['body'] = curl_exec($curl);
      $this->response['statusCode'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);

      if (!empty($options['format']) && $options['format'] === 'json') {
        $this->parseJsonResponse();
      }

      if ($this->debug || $this->advDebug) {
        $info = curl_getinfo($curl);
        echo '<pre>';
        print_r($info);
        echo '</pre>';
        if ($info['http_code'] == 0) {
          echo '<br>cURL error num: ' . curl_errno($curl);
          echo '<br>cURL error: ' . curl_error($curl);
        }
        echo '<br>Sent info:<br><pre>';
        print_r($data);
        echo '</pre>';
      }
    } catch (Exception $ex) {
      if ($this->debug || $this->advDebug) {
        echo '<br>cURL error num: ' . curl_errno($curl);
        echo '<br>cURL error: ' . curl_error($curl);
      }
      echo 'Error on cURL';
      $this->response = null;
    }

    curl_close($curl);

    // Cast to object for easier access
    $this->response = (object) $this->response;
    if (isset($this->response->headers)) {
      $this->response->headers = (object) $this->response->headers;
    }

    return $this->response;
  }

  private function buildURL($url, array $options) {
    $options = http_build_query($options);
    $url = urlencode($url);
    $url = $this->endPointUrl . '&url=' . $url . '&' . $options;

    return $url;
  }

  private function processResponseHeaders($curl, $header) {
    $headerSplit = preg_split('/:/', $header);
    $headerName = $headerSplit[0];
    unset($headerSplit[0]);
    $value = isset($headerSplit[1]) ? trim(implode(':', $headerSplit)) : '';
    if (is_numeric($value)) {
      $value = (int) $value;
    }
    $this->response['headers'][$headerName] = $value;

    return strlen($header);
  }

  private function parseJsonResponse() {
    $json = json_decode($this->response['body']);
    $this->response['headers']['original_status'] = $json->original_status;
    $this->response['headers']['pc_status'] = $json->pc_status;
    $this->response['headers']['url'] = $json->url;
  }

}
