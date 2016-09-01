# CoRex Client
Various clients for php (curl-wrapper, rest-client, etc.)

These clients are designed to be as simple as possible and therefore there will only be a few examples. The rest should be easy to understand and implement.

## HttpClient

### Features
- Set user-agent. Default none.
- Set header.
- Set content-type (header).
- Set accept-type (header).
- Methods to set path, query, post fields and complete body.
- Methods to get headers for request/response.
- Methods to get(), post(), put(), delete().

### Example
```php
$httpClient = new HttpClient();
$httpClient->setUrl('https://jsonplaceholder.typicode.com/albums');
if ($httpClient->get()) {
    var_dump($httpClient->getResponse());
} else {
    print('Oh no, something is wrong.');
    var_dump($httpClient->getHttpCode());
    var_dump($httpClient->getResponseHeaders());
}
```

## RestClient

### Features
- Methods to set path, query and request fields.
- Methods to get(), post(), put(), delete().
- Methods to get raw response.
- Methods to get response. Uses dot notation.

### Example
```php
$restClient = new RestClient();
$restClient->setUrl('https://jsonplaceholder.typicode.com/albums');
if ($restClient->get()) {
    var_dump($restClient->getResponseRaw());
    var_dump($restClient->getResponse());
} else {
    print('Oh no, something is wrong.');
    var_dump($restClient->getHttpClient()->getHttpCode());
    var_dump($restClient->getHttpClient()->getResponseHeaders());
}
```
