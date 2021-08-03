# ProxyCrawl API PHP class

A lightweight, dependency free PHP class that acts as wrapper for ProxyCrawl API.

## Installing

Choose a way of installing:

- Use [Packagist](https://packagist.org/packages/proxycrawl/proxycrawl) PHP package manager.
- Download the project from Github and save it into your project so you can require it `require_once('proxycrawl-php/src/[class].php')`

## Crawling API

First initialize the CrawlingAPI class. You can [get your free token here](https://proxycrawl.com/signup?signup=github).

```php
$api = new ProxyCrawl\CrawlingAPI(['token' => 'YOUR_PROXYCRAWL_TOKEN']);
```

### GET requests

Pass the url that you want to scrape plus any options from the ones available in the [API documentation](https://proxycrawl.com/docs/crawling-api/).

```php
$api->get(string $url, array $options = []);
```

Example:

```php
$response = $api->get('https://www.facebook.com/britneyspears');
if ($response->statusCode === 200) {
  echo $response->body;
}
```

You can pass any options from ProxyCrawl API.

Example:

```php
$response = $api->get('https://www.reddit.com/r/pics/comments/5bx4bx/thanks_obama/', [
  'user_agent' => 'Mozilla/5.0 (Windows NT 6.2; rv:20.0) Gecko/20121202 Firefox/30.0',
  'format' => 'json'
]);
if ($response->statusCode === 200) {
  echo $response->body;
}
```

Optionally pass [store](https://proxycrawl.com/docs/crawling-api/parameters/#store) parameter to `true` to store a copy of the API response in the [ProxyCrawl Cloud Storage](https://proxycrawl.com/dashboard/storage).

Example:

```php
$response = $api->get('https://www.reddit.com/r/pics/comments/5bx4bx/thanks_obama/', [
  'store' => true
]);

if ($response->statusCode === 200) {
  echo 'storage url: ' . $response->headers->storage_url . PHP_EOL;
}
```

### POST requests

Pass the url that you want to scrape, the data that you want to send which can be either a json or a string, plus any options from the ones available in the [API documentation](https://proxycrawl.com/docs/crawling-api/).

```php
$api->post(string $url, array or string $data, array options = []);
```

Example:

```php
$response = $api->post('https://producthunt.com/search', ['text' => 'example search']);
if ($response->statusCode === 200) {
  echo $response->body;
}
```

You can send the data as `application/json` instead of `x-www-form-urlencoded` by setting option `post_content_type` as json.

```php
$response = $api->post('https://httpbin.org/post', json_encode(['some_json' => 'with some value']), ['post_content_type' => 'json']);
if ($response->statusCode === 200) {
  echo $response->body;
}
```

### PUT requests

Pass the url that you want to scrape, the data that you want to send which can be either a json or a string, plus any options from the ones available in the [API documentation](https://proxycrawl.com/docs/crawling-api/).

```php
$api->put(string $url, array or string $data, array options = []);
```

Example:

```php
$response = $api->put('https://producthunt.com/search', ['text' => 'example search']);
if ($response->statusCode === 200) {
  echo $response->body;
}
```

### Javascript requests

If you need to scrape any website built with Javascript like React, Angular, Vue, etc. You just need to pass your javascript token and use the same calls. Note that only `->get` is available for javascript and not `->post`.

```php
$api = new ProxyCrawl\CrawlingAPI(['token' => 'YOUR_JAVASCRIPT_TOKEN']);
```

```php
$response = $api->get('https://www.nfl.com');
if ($response->statusCode === 200) {
  echo $response->body;
}
```

Same way you can pass javascript additional options.

```php
$response = $api->get('https://www.freelancer.com', ['page_wait' => 5000]);
if ($response->statusCode === 200) {
  echo $response->body;
}
```

## Original status

You can always get the original status and proxycrawl status from the response. Read the [ProxyCrawl documentation](https://proxycrawl.com/docs/crawling-api/) to learn more about those status.

```php
$response = $api->get('https://craiglist.com');
echo $response->headers->original_status . PHP_EOL;
echo $response->headers->pc_status . PHP_EOL;
```

## Scraper API

First initialize the ScraperAPI class. You can [get your free token here](https://proxycrawl.com/signup?signup=github). Please note that only some websites are supported, check the [API documentation](https://proxycrawl.com/docs/scraper-api/) for more information.

```php
$api = new ProxyCrawl\ScraperAPI(['token' => 'YOUR_PROXYCRAWL_TOKEN']);
```

Pass the url that you want to scrape plus any options from the ones available in the [API documentation](https://proxycrawl.com/docs/scraper-api/).

Example:

```php
$response = $api->get('https://www.amazon.com/DualSense-Wireless-Controller-PlayStation-5/dp/B08FC6C75Y/');
echo 'status code: ' . $response->statusCode . PHP_EOL;
if ($response->statusCode === 200) {
  var_dump($response->json); // Will print scraped Amazon details
}
```

## Leads API

First initialize the LeadsAPI class. You can [get your free token here](https://proxycrawl.com/signup?signup=github).

```php
$api = new ProxyCrawl\LeadsAPI(['token' => 'YOUR_PROXYCRAWL_TOKEN']);
```

Pass the domain where you want to search for leads.

Example:

```php
$response = $api->getFromDomain('target.com');
if ($response->statusCode === 200) {
  foreach ($response->json->leads as $key => $lead) {
    echo $lead->email . PHP_EOL;
  }
}
```

## Screenshots API usage

Initialize with your Screenshots API token and call the `get` method.

```php
$api = new ProxyCrawl\ScreenshotsAPI(['token' => 'YOUR_PROXYCRAWL_TOKEN']);
$response = $api->get('https://www.apple.com');
echo 'success: ' . $response->headers->success . PHP_EOL;
echo 'remaining requests: ' . $response->headers->remaining_requests . PHP_EOL;
file_put_contents('apple.jpg', $response->body);
```

or you can specify a callback that automatically saves the file to the temporary folder

```php
$api = new ProxyCrawl\ScreenshotsAPI(['token' => 'YOUR_PROXYCRAWL_TOKEN']);
$response = $api->get('https://www.apple.com', [
  'callback' => function($filepath) {
    echo 'filepath: ' . $filepath . PHP_EOL;
  }
]);
echo 'success: ' . $response->headers->success . PHP_EOL;
echo 'remaining requests: ' . $response->headers->remaining_requests . PHP_EOL;
```

or specifying a file path via `saveToPath` option

```php
$api = new ProxyCrawl\ScreenshotsAPI(['token' => 'YOUR_PROXYCRAWL_TOKEN']);
$response = $api->get('https://www.apple.com', [
  'saveToPath' => 'apple.jpg',
  'callback' => function($filepath) {
    echo 'filepath: ' . $filepath . PHP_EOL;
  }
]);
echo 'success: ' . $response->headers->success . PHP_EOL;
echo 'remaining requests: ' . $response->headers->remaining_requests . PHP_EOL;
```

Note that `$api.get(url, options)` method accepts an [options](https://proxycrawl.com/docs/screenshots-api/parameters)

## Storage API usage

Initialize the Storage API using your private token.

```php
$api = new ProxyCrawl\StorageAPI(['token' => 'YOUR_PROXYCRAWL_TOKEN']);
```

Pass the [url](https://proxycrawl.com/docs/storage-api/parameters/#url) that you want to get from [Proxycrawl Storage](https://proxycrawl.com/dashboard/storage).

```php
$response = $api->get('https://www.apple.com');

echo 'status code: ' . $response->statusCode . PHP_EOL;
if ($response->statusCode === 200) {
  echo 'body: ' . $response->body . PHP_EOL;
  echo 'original status: ' . $response->headers->original_status . PHP_EOL;
  echo 'proxycrawl status: ' . $response->headers->pc_status . PHP_EOL;
  echo 'rid: ' . $response->headers->rid . PHP_EOL;
  echo 'url: ' . $response->headers->url . PHP_EOL;
  echo 'stored date: ' . $response->headers->stored_at . PHP_EOL;
}
```

or you can use the [RID](https://proxycrawl.com/docs/storage-api/parameters/#rid)

```php
$response = $api->get('RID_REPLACE');

echo 'status code: ' . $response->statusCode . PHP_EOL;
if ($response->statusCode === 200) {
  echo 'body: ' . $response->body . PHP_EOL;
  echo 'original status: ' . $response->headers->original_status . PHP_EOL;
  echo 'proxycrawl status: ' . $response->headers->pc_status . PHP_EOL;
  echo 'rid: ' . $response->headers->rid . PHP_EOL;
  echo 'url: ' . $response->headers->url . PHP_EOL;
  echo 'stored date: ' . $response->headers->stored_at . PHP_EOL;
}
```

Note: One of the two RID or URL must be sent. So both are optional but it's mandatory to send one of the two.

### [Delete](https://proxycrawl.com/docs/storage-api/delete/) request

To delete a storage item from your storage area, use the correct RID

```php
if ($api->delete('RID_REPLACE')) {
  echo 'delete success' . PHP_EOL;
  echo 'status code: ' . $api->response->statusCode . PHP_EOL;
} else {
  echo 'delete failed' . PHP_EOL;
  echo 'status code: ' . $api->response->statusCode . PHP_EOL;
}
```

### [Bulk](https://proxycrawl.com/docs/storage-api/bulk/) request

To do a bulk request with a list of RIDs, please send the list of rids as an array

```php
$items = $api->bulk(['RID1', 'RID2', 'RID3', ...]);
foreach ($items as $item) {
  echo 'body: ' . $item->body . PHP_EOL;
  echo 'stored at: ' . $item->stored_at . PHP_EOL;
  echo 'original status: ' . $item->original_status . PHP_EOL;
  echo 'proxycrawl status: ' . $item->pc_status . PHP_EOL;
  echo 'rid: ' . $item->rid . PHP_EOL;
  echo 'url: ' . $item->url . PHP_EOL;
  echo PHP_EOL;
}
```

### [RIDs](https://proxycrawl.com/docs/storage-api/rids) request

To request a bulk list of RIDs from your storage area

```php
$rids = $api->rids();
foreach ($rids as $rid) {
  echo $rid . PHP_EOL;
}
```

You can also specify a limit as a parameter

```php
$rids = $api->rids(10);
```

### [Total Count](https://proxycrawl.com/docs/storage-api/total_count)

To get the total number of documents in your storage area

```php
$totalCount = $api->totalCount();
echo 'total count: ' . $totalCount . PHP_EOL;
```

If you have questions or need help using the library, please open an issue or [contact us](https://proxycrawl.com/contact).

---

Copyright 2021 ProxyCrawl
