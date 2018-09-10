# Http Helper [![Build Status](https://travis-ci.org/carstenwindler/http-helper.svg?branch=master)](https://travis-ci.org/carstenwindler/http-helper)

A collection of helper functions for your everyday work with http requests and responses.

Supports 
* PSR-7 (`Psr\Http\Message\RequestInterface`, `Psr\Http\Message\ResponseInterface`)
* Symfony HttpFoundation (`Symfony\Component\HttpFoundation\Request`, `Symfony\Component\HttpFoundation\Response`) 

The main idea of these functions was to provide quick access to the http information during development (e.g. via the debugger console), that's why the functions are not namespaced. *It's not meant to be used in your actual code*! Srsly, it's not.

# Example

Here are some http-helper functions in action:

![Basic usage](http://media.carstenwindler.de/packages/http-helper/http-helper-example-basic-usage.gif "Basic usage of http-helper")

# Installation

`composer require-dev carstenwindler/http-helper`

(I suggest to only add these functions as `require-dev`).

The functions are registered using the composer autoload feature, so there is nothing more for you to do.

# Helper functions

## Request

Both `Psr\Http\Message\RequestInterface` and `Symfony\Component\HttpFoundation\Request` are supported by the following functions. 

### request_to_string()

Serializes the request object in plain HTTP format according to [RFC 7230](https://tools.ietf.org/html/rfc7230#page-19).

### request_to_file()

Just like _request_to_string()_, but the request string will be stored in a file called `request.http` in your DOCUMENT_ROOT.

This way you can e.g. easily execute the request using PhpStorms cool [Editor-based Rest Client](https://blog.jetbrains.com/phpstorm/2017/09/editor-based-rest-client/).

If the file already exists and is not empty, the request will be appended.

### request_to_curl()

Returns the request object as cURL command, so it can be used e.g. on console or import it in Postman easily.

## Response

Both `Psr\Http\Message\ResponseInterface` and `Symfony\Component\HttpFoundation\Response` are supported by the following functions. 

### response_to_string()

Serializes the response object in plain HTTP format according to [RFC 7230](https://tools.ietf.org/html/rfc7230#page-19).

### response_to_file()

Just like `response_to_string()`, but the response string will be stored in a file called `response.http` in your DOCUMENT_ROOT.

# File usage

The following example shows you how to store requests and responses in files easily. 

![Advanced usage example](http://media.carstenwindler.de/packages/http-helper/http-helper-example-file-usage.gif "Basic usage of http-helper")

But why would you do that? Here, PhpStorm comes into play. It now comes equipped with the cool [Editor-based Rest Client](https://blog.jetbrains.com/phpstorm/2017/09/editor-based-rest-client/) (think of it like a very simple Postman without all the fancy gui).

*http-helper* uses the suffix `.http`, which is recognised by PhpStorm, so you will immediately have the possibility to modify and rerun the request in that file. I quite like the idea, since you could add it to your code base, so your fellow devs would have immediate access.

# Credits

The serialization of the PSR-7 requests and responses are done using [Zend Diactoros](https://github.com/zendframework/zend-diactoros). Weird name, great library!

For Symfony, the build-in serializers are used. Nice.

# Todo

* make storage path of `*_to_file()` configurable
