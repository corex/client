# CoRex Client
Various clients for php (Http, Rest, etc.)

## Http\Client
- Support for specifying base-url through constructor and/or by method.
- Support for specifying user agent.
- Support for headers.
- Support for specifying base-url with tokens and replace by method or request-class.
- Support for specifying query fields.
- Support for specifying request fields.
- Support for specifying custom body.
- Support for getting response header(s).
- Support for getting debug-information.
- Support for request-class with constructor based properties.
- Support for get(), post(), put() and delete().

The http client can be used in many ways. It is highly recommended to look at method-documentation in various classes.

Example of requesting data from a resource.
```php
$client = new Client('https://jsonplaceholder.typicode.com/albums');
$response = $client->get();
```

To check if request was ok, simply check the http-code by using $client->getHttpCode().
If anything else than 200, null is returned. You can get the message for the http-code by using ....
```php
$message = HttpCode::getMessage($client->getHttpCode());
```

To use request-class, you can do it like this ....
```php
$request = new Request([
    'userId' => 1,
    'id' => 3,
    'title' => 'Some title',
    'body' => 'Some body'
]);
$client = new Client('https://jsonplaceholder.typicode.com/posts');
$response = $client->post($request);
```
.... and parse it to i.e. post().

First parameter for constructor is fields, second is query and last is path.
So there is a big advantage to extend this class and set some default parameters in constructor.

Example of requesting data from a resource with path token and class extending.
```php
class MyRequest extends Request
{
    public $userId;
    public $id;
    public $title;
    public $body;

    public function __construct()
    {
        parent::__construct(
            [
                'resource' => 'posts',
            ]
        );
    }
}

$request = new MyRequest();
$request->userId = 1;
$request->id = 3;
$request->title = 'Some title';
$request->body = 'Some body';

$client = new Client('https://jsonplaceholder.typicode.com/{resource}');
$response = $client->post($request);
```
In this example, a class extends request class, setting resource from constructor and have 4 properties to set.
If you specify properties in class (above example) and also sets the same field in constructor,
this field will be overwritten by property in class. All properties in classes will be considered a field on client.

**Please note that the client might throw exceptions.**

## Rest\Client
- Support for getting data out of response using dot notation.
- Support for handling response through object properties (DataList / DataObject).

The Rest\Client is pretty much the same as Http\Client since Rest\Client extends Http\Client.
Except that when you use get(), post(), put() and delete(), the data returned will be an instance of Response class.

### Response
Basic methods exists for getting data out of response-object using dot-notation.
You can extend the existing Response class and parse the class as secondary parameter for client operation.
Note that if you do this, you might not have code completion on all of your methods.

Example of getting title from object no. 5.
```php
$client = new Client('https://jsonplaceholder.typicode.com/albums');
$response = $client->get();
$title = $response->get('4.title');
```

Example of getting data using DataList/DataObject class.
```php
class AlbumData extends DataObject
{
    public $userId;
    public $id;
    public $title;
}

class AlbumList extends DataList
{
    public function current()
    {
        return new AlbumData(parent::current());
    }
}

$client = new Client('https://jsonplaceholder.typicode.com/albums');
$response = $client->get();
$albums = new AlbumList($response);
foreach ($albums as $album) {
    print($album->title . "\n");
}
```
