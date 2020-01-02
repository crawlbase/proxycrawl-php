# ProxyCrawl API PHP class

A lightweight, dependency free PHP class that acts as wrapper for ProxyCrawl API.

## Installing

Choose a way of installing:

- Download the php class from Github.
- Or use [Packagist](https://packagist.org/packages/proxycrawl/proxycrawl) PHP package manager.

Then require the `proxycrawl-api.php` file.

## Class usage

First initialize the ProxyCrawlAPI class. You can [get your free token here](https://proxycrawl.com/signup).

```php
$api = new ProxyCrawlAPI(['token' => 'YOUR_PROXYCRAWL_TOKEN']);
```

### GET requests

Pass the url that you want to scrape plus any options from the ones available in the [API documentation](https://proxycrawl.com/dashboard/docs).

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

Pass the url that you want to scrape, the data that you want to send which can be either a json or a string, plus any options from the ones available in the [API documentation](https://proxycrawl.com/dashboard/docs).

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

Pass the url that you want to scrape, the data that you want to send which can be either a json or a string, plus any options from the ones available in the [API documentation](https://proxycrawl.com/dashboard/docs).

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
$api = new ProxyCrawlAPI(['token' => 'YOUR_JAVASCRIPT_TOKEN']);
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

You can always get the original status and proxycrawl status from the response. Read the [ProxyCrawl documentation](https://proxycrawl.com/dashboard/docs) to learn more about those status.

```php
$response = $api->get('https://craiglist.com');
echo $response->headers->original_status;
echo $response->headers->pc_status;
```

If you have questions or need help using the library, please open an issue or [contact us](https://proxycrawl.com/contact).

---

Copyright 2020 ProxyCrawl
