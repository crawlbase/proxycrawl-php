# ProxyCrawl API PHP class

A lightweight, dependency free PHP class that acts as wrapper for ProxyCrawl API.

## Installing

Choose a way of installing:

- Use [Packagist](https://packagist.org/packages/proxycrawl/proxycrawl) PHP package manager.
- Download the project from Github and save it into your project so you can require it `require_once('proxycrawl-php/src/[class].php')`

### Upgrading to version 2

Version 2 deprecates the usage of ProxyCrawlAPI (although is still usable but will be removed in future versions) in favour of ProxyCrawl\CrawlingAPI. Please test the upgrade before deploying to production.

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

## Scraper API

First initialize the ScraperAPI class. You can [get your free token here](https://proxycrawl.com/signup?signup=github). Please note that only some websites are supported, check the [API documentation](https://proxycrawl.com/docs/scraper-api/) for more information.

```php
$api = new ProxyCrawl\ScraperAPI(['token' => 'YOUR_PROXYCRAWL_TOKEN']);
```

Pass the url that you want to scrape plus any options from the ones available in the [API documentation](https://proxycrawl.com/docs/scraper-api/).

Example:

```php
$response = $api->get('https://www.amazon.com/DualSense-Wireless-Controller-PlayStation-5/dp/B08FC6C75Y/');
if ($response->statusCode === 200) {
  echo $response->json->name; // Will print the Amazon item name.
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
    echo $lead->email;
  }
}
```

## Original status

You can always get the original status and proxycrawl status from the response. Read the [ProxyCrawl documentation](https://proxycrawl.com/docs/crawling-api/) to learn more about those status.

```php
$response = $api->get('https://craiglist.com');
echo $response->headers->original_status;
echo $response->headers->pc_status;
```

If you have questions or need help using the library, please open an issue or [contact us](https://proxycrawl.com/contact).

---

Copyright 2021 ProxyCrawl
