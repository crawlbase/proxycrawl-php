<?php namespace ProxyCrawl;

require_once('base-api.php');

/**
 * A PHP class that acts as wrapper for ProxyCrawl Leads API.
 *
 * Read ProxyCrawl API documentation https://proxycrawl.com/docs/leads-api/
 *
 * Copyright ProxyCrawl
 * Licensed under the Apache License 2.0
 */
class LeadsAPI extends BaseAPI {

  protected $basePath = 'leads';

  public function getFromDomain($domain, array $options = []) {
    $options['domain'] = $domain;
    return $this->request($options);
  }

}
