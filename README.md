# Http Helper

A tiny collection of helper functions which might be useful for your everyday work with http requests.

Currently only **PSR-7 compliant** requests (i.e. those which implement Psr\Http\Message\RequestInterface) are supported. 

## requestToString()

Serializes the request object in plain HTTP format according to [RFC 7230](https://tools.ietf.org/html/rfc7230#page-19).

## requestToFile()

Just like _requestToString()_, but the request will be stored in a file called `request.http` in your DOCUMENT_ROOT.

This way you can e.g. easily execute the request using PhpStorms cool [Editor-based Rest Client](https://blog.jetbrains.com/phpstorm/2017/09/editor-based-rest-client/).

## requestToCurl()

Returns the request object as cURL command, so it can be used e.g. on console or import it in Postman easily.

# Credits

The serialization of the PSR-7 request is done using [Zend Diactoros](https://github.com/zendframework/zend-diactoros). Weird name, great library!

# Todo

* setup TravisCI
* make storage path of _requestToFile()_ configurable
* support symfony/http-foundation