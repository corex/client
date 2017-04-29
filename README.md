# CoRex Client
Various clients for php (Http, Rest, etc.)

**_Versioning for this package follows http://semver.org/. Backwards compatibility might break on upgrade to major versions._**

Supported methods: get, post, put, delete, patch, options.

A typical flow would be ...
- Create and set properties on client.
- Create and set properties on request.
- Call client with request (properties on client and request are merged).
- Use response to get data (various methods exists to get data).


**Please note that the client might throw exceptions.**


## Base client (abstract)
All clients extends base client which means there will be a common set of methods available.
- Url can be specified on constructor.
- baseUrl() - Specify base url (overwrite base url set through constructor).
- timeout() - Specify timeout in seconds.
- token() - Specify token. Tokens are used to specify {} in path i.e. a path could be "/user/{id}" and calling token('id', 4') will result in "/user/4".
- param() - Specify parameter. Example: param('param', 'test') will be added as [?/&]param=test on url. All parameters are url encoded.
- header() - Specify request header(). Example: header('Accept', 'application/json').
- userAgent() - Specify user agent.
- getDebug() - Get debug information (response not returned).

Normally, setting tokens, parameters and headers on request will override tokens, parameters and headers on client. However, it is possible to specify them as final on client.


## Base request (abstract)
- path() - Specify path. Will be added to url.
- token() - Specify token (explained elsewhere). It will override client unless it is set as final.
- param() - Specify parameter (explained elsewhere). It will override client unless it is set as final.
- header() - Specify header (explained elsewhere). It will override client unless it is set as final.


## Base response (abstract)
- header() - Get response header.
- headers() - Get response headers.
- body() - Get body.
- status() - Get status (http status). If request succeeded, it will return 200. Get messages through class Status.


## Http\Client (extends base client)
Methods inherited from base response.

A few examples.
```php
// Get 1 post.
$client = new Rest\Client('https://jsonplaceholder.typicode.com/posts/1');
$response = $client->call(Method::GET);
var_dump($response);

// Get 1 post by token on request.
$client = new Http\Client('https://jsonplaceholder.typicode.com/posts/{id}');
$request = (new Http\Request())->token('id', 1);
$response = $client->call(Method::GET, $request);
var_dump($response);

// Get 1 post by path and token on request.
$client = new Http\Client('https://jsonplaceholder.typicode.com');
$request = (new Http\Request())->path('/posts/{id}')->token('id', 1);
$response = $client->call(Method::GET, $request);
var_dump($response);
```


## Http\Request (extends base request)
- body() Set body.

A few examples.
```php
// Create request with path and token on request.
$request = new Http\Request();
$request->path('/posts/{id}');
$request->token('id', 1);
var_dump($request);

// Create request with header.
$request = new Http\Request();
$request->header('Accept', 'application/json');
var_dump($request);

// Create request with query parameter fields.
$request = new Http\Request();
$request->param('fields', 'firstname,lastname');
var_dump($request);

// Create request with body set.
$request = new Http\Request();
$request->body('{"something":["test1","test2"]}');
var_dump($request);
```


## Http\Response (extends base response)
Methods inherited from base response.

A few examples.
```php
// Get body from response.
$client = new Http\Client('https://jsonplaceholder.typicode.com/posts/1');
$response = $client->call(Method::GET);
var_dump($response->body());

// Get status + status-message from response.
$client = new Http\Client('https://jsonplaceholder.typicode.com/unknown');
$response = $client->call(Method::GET);
if ($response->status() != 200) {
    var_dump($response->status());
    var_dump(Status::message($response->status()));
}

// Get response headers.
$client = new Http\Client('https://jsonplaceholder.typicode.com/posts/1');
$response = $client->call(Method::GET);
var_dump($response->headers());

// Get Content-Type from response headers.
$client = new Http\Client('https://jsonplaceholder.typicode.com/posts/1');
$response = $client->call(Method::GET);
var_dump($response->header('Content-Type'));
```


## Rest\Client (extends base client)
Methods inherited from base response.

A few examples.
```php
// Get 1 post.
$client = new Rest\Client('https://jsonplaceholder.typicode.com/posts/1');
$response = $client->call(Method::GET);
var_dump($response);

// Get 1 post by token on request.
$client = new Rest\Client('https://jsonplaceholder.typicode.com/posts/{id}');
$request = (new Rest\Request())->token('id', 1);
$response = $client->call(Method::GET, $request);
var_dump($response);

// Get 1 post by path and token on request.
$client = new Rest\Client('https://jsonplaceholder.typicode.com');
$request = (new Rest\Request())->path('/posts/{id}')->token('id', 1);
$response = $client->call(Method::GET, $request);
var_dump($response);
```


## Rest\Request (extends base request)
- field() - 

A few examples.
```php
// Create request with path and token on request.
$request = new Rest\Request();
$request->path('/posts/{id}');
$request->token('id', 1);
var_dump($request);

// Create request with header.
$request = new Rest\Request();
$request->header('Accept', 'application/json');
var_dump($request);

// Create request with query parameter fields.
$request = new Rest\Request();
$request->param('fields', 'firstname,lastname');
var_dump($request);

// Create request with fields.
$request = new Rest\Request();
$request->field('firstname', 'Roger');
$request->field('lastname', 'Moore');
var_dump($request);
```


## Rest\Response (extends base response)
- value() - 
- toArray() - 

A few examples.
```php
// Get body from response.
$client = new Rest\Client('https://jsonplaceholder.typicode.com/posts/1');
$response = $client->call(Method::GET);
var_dump($response->body());

// Get status + status-message from response.
$client = new Rest\Client('https://jsonplaceholder.typicode.com/unknown');
$response = $client->call(Method::GET);
if ($response->status() != 200) {
    var_dump($response->status());
    var_dump(Status::message($response->status()));
}

// Get response headers.
$client = new Rest\Client('https://jsonplaceholder.typicode.com/posts/1');
$response = $client->call(Method::GET);
var_dump($response->headers());

// Get Content-Type from response headers.
$client = new Rest\Client('https://jsonplaceholder.typicode.com/posts/1');
$response = $client->call(Method::GET);
var_dump($response->header('Content-Type'));

// Get title from response. Dot notation supported.
$client = new Rest\Client('https://jsonplaceholder.typicode.com/posts/1');
$response = $client->call(Method::GET);
var_dump($response->value('title'));

// Get response as array.
$client = new Rest\Client('https://jsonplaceholder.typicode.com/posts/1');
$response = $client->call(Method::GET);
var_dump($response->toArray());
```


## Rest\Entity (abstract) / Rest\Collection (abstract)
- toArray() - 

An example of using Entity and Collection.
```php
class Album extends Entity
{
    public $userId;
    public $id;
    public $title;
}

class Albums extends Collection
{
    public function current()
    {
        return new Album(parent::current());
    }
}

// Using above classes will make you able to iterate over album/albums and have auto-completion.
$client = new Rest\Client('https://jsonplaceholder.typicode.com/albums');
$response = $client->call(Method::GET);
$albums = new Albums($response);
foreach ($albums as $album) {
    print($album->title . "\n");
    var_dump($album->toArray());
}
```
