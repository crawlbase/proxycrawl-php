<?php namespace ProxyCrawl;

require_once('base-api.php');

/**
 * A PHP class that acts as wrapper for ProxyCrawl Crawling API.
 *
 * Read ProxyCrawl API documentation https://proxycrawl.com/docs/crawling-api/
 *
 * Copyright ProxyCrawl
 * Licensed under the Apache License 2.0
 */
class CrawlingAPI extends BaseAPI {

  public function get($url, array $options = []) {
    $options['url'] = $url;
    return $this->request($options);
  }

  public function post($url, $data, array $options = []) {
    $options['url'] = $url;
    if (!isset($options['method'])) {
      $options['method'] = 'POST';
    }
    if (is_array($data)) {
      $data = http_build_query($data);
    }
    return $this->request($options, $data);
  }

  public function put($url, $data, array $options = []) {
    $options['method'] = 'PUT';
    return $this->post($url, $options, $data);
  }

}
