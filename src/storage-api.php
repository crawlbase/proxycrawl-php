<?php namespace ProxyCrawl;

require_once('base-api.php');

/**
 * A PHP class that acts as wrapper for ProxyCrawl Storage API.
 *
 * Read ProxyCrawl API documentation https://proxycrawl.com/docs/storage-api/
 *
 * Copyright ProxyCrawl
 * Licensed under the Apache License 2.0
 */
class StorageAPI extends BaseAPI {

  public function get($urlOrRid, array $options = array()) {
    if (empty($urlOrRid)) {
      throw new \Exception('Either URL or RID is required');
    }
    if (substr($urlOrRid, 0, 4) === 'http') {
      $options['url'] = $urlOrRid;
    } else {
      $options['rid'] = $urlOrRid;
    }
    $this->setEndpoint('storage');
    return $this->request($options);
  }

  public function delete($rid, array $options = array()) {
    if (empty($rid)) {
      throw new \Exception('One or more RIDs are required');
    }
    $options['rid'] = $rid;
    $options['method'] = 'DELETE';
    $this->setEndpoint('storage');
    $this->request($options);

    return $this->response->statusCode === 200;
  }

  public function bulk(array $rids, array $options = array()) {
    if (count($rids) === 0) {
      throw new \Exception('One or more RIDs are required');
    }
    $data = json_encode(['rids' => $rids]);
    $options['method'] = 'POST';
    $options['beforeCurlExecCallback'] = function($curl) {
      curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'
      ));
    };

    $this->setEndpoint('storage/bulk');
    $this->request($options, $data);
    return $this->response->json;
  }

  public function rids($limit = -1, array $options = array()) {
    if ($limit > -1) {
      $options['limit'] = $limit;
    }
    $this->setEndpoint('storage/rids');
    $this->request($options);
    return $this->response->json;
  }

  public function totalCount(array $options = array()) {
    $this->setEndpoint('storage/total_count');
    $this->request($options);
    return $this->response->json->totalCount;
  }
}
