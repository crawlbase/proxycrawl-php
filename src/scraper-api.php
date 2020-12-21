<?php namespace ProxyCrawl;

require_once('base-api.php');

/**
 * A PHP class that acts as wrapper for ProxyCrawl Scraper API.
 *
 * Read ProxyCrawl API documentation https://proxycrawl.com/docs/scraper-api/
 *
 * Copyright ProxyCrawl
 * Licensed under the Apache License 2.0
 */
class ScraperAPI extends BaseAPI {

  protected $basePath = 'scraper';

  public function get($url, array $options = []) {
    $options['url'] = $url;
    return $this->request($options);
  }

  public function post($url, $data, array $options = []) {
    throw new Exception('POST is not supported on the Scraper API');
  }

  public function put($url, $data, array $options = []) {
    throw new Exception('PUT is not supported on the Scraper API');
  }

}
