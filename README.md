# Http Helper

A tiny collection of helper functions for your everyday work with http requests and responses.

Supports 
* PSR-7 (`Psr\Http\Message\RequestInterface`, `Psr\Http\Message\ResponseInterface`)
* Symfony HttpFoundation (`Symfony\Component\HttpFoundation\Request`, `Symfony\Component\HttpFoundation\Response`) 

The main idea of these functions was to provide quick access to the http information during development (e.g. via the debugger console), that's why the functions are not namespaced. *It's not meant to be used in your actual code*!

# Installation

`composer require-dev carstenwindler/http-helper`

(I suggest to use these functions as `require-dev` only, however of course they can also be used as require).

The functions are registered using the composer autoload feature, so there is nothing more for you to do.

# Helper functions

## Request

Both `Psr\Http\Message\RequestInterface` and `Symfony\Component\HttpFoundation\Request` are supported by the following functions. 

### request_to_string()

Serializes the request object in plain HTTP format according to [RFC 7230](https://tools.ietf.org/html/rfc7230#page-19).

### request_to_file()

Just like _request_to_string()_, but the request string will be stored in a file called `request.http` in your DOCUMENT_ROOT.

This way you can e.g. easily execute the request using PhpStorms cool [Editor-based Rest Client](https://blog.jetbrains.com/phpstorm/2017/09/editor-based-rest-client/).

### request_to_curl()

Returns the request object as cURL command, so it can be used e.g. on console or import it in Postman easily.

## Response

Both `Psr\Http\Message\ResponseInterface` and `Symfony\Component\HttpFoundation\Response` are supported by the following functions. 

### response_to_string()

Serializes the response object in plain HTTP format according to [RFC 7230](https://tools.ietf.org/html/rfc7230#page-19).

### response_to_file()

Just like `response_to_string()`, but the response string will be stored in a file called `response.http` in your DOCUMENT_ROOT.

# Credits

The serialization of the PSR-7 requests and responses are done using [Zend Diactoros](https://github.com/zendframework/zend-diactoros). Weird name, great library!

For Symfony, the build-in serializers are used.

# Todo

* setup TravisCI
* make storage path of `*_to_file()` configurable