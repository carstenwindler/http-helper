<?php

use Zend\Diactoros\Request\Serializer as RequestSerializer;
use Zend\Diactoros\Response\Serializer as ResponseSerializer;

if (!function_exists('request_to_string')) {
    /**
     * Serializes a Request (PSR7 or Symfony Http Foundation) into http format
     *
     * @param Psr\Http\Message\RequestInterface|Symfony\Component\HttpFoundation\Request $request
     * @return string
     */
    function request_to_string($request)
    {
        $requestString = "unknown\r\n";

        if (
            class_exists('Symfony\Component\HttpFoundation\Request') &&
            $request instanceof Symfony\Component\HttpFoundation\Request
        ) {
            $requestString = $request->__toString();
        }

        if (
            interface_exists('Psr\Http\Message\RequestInterface') &&
            $request instanceof Psr\Http\Message\RequestInterface
        ) {
            $requestString = RequestSerializer::toString($request);
        }

        return $requestString;
    }
}

if (!function_exists('request_to_curl')) {
    /**
     * Serializes  a Request (PSR7 or Symfony Http Foundation) into curl syntax
     *
     * @param Psr\Http\Message\RequestInterface|Symfony\Component\HttpFoundation\Request $request
     * @return string
     */
    function request_to_curl($request)
    {
        $curl = sprintf('curl -X %s %s', $request->getMethod(), $request->getUri());

        foreach ($request->getHeaders() as $name => $values) {
            $curl .= sprintf(" -H '%s'", $name . ": " . implode(", ", $values));
        }

        $body = (string)$request->getBody();

        if ($body) {
            $curl .= sprintf(" -d '%s'", $body);
        }

        return $curl;
    }
}

if (!function_exists('request_to_file')) {
    /**
     * Serializes a Request (PSR7 or Symfony Http Foundation) into http format and writes it into a file.
     * If the file already exists, the string will be appended, using PhpStorm .http formats ###
     * as separator
     *
     * @param Psr\Http\Message\RequestInterface|Symfony\Component\HttpFoundation\Request $request
     * @param string|null $path
     * @return string
     */
    function request_to_file($request, $path = null)
    {
        $httpRequest = request_to_string($request);

        if (empty($path)) {
            $path = $_SERVER['DOCUMENT_ROOT'];
        }

        if (empty($path)) {
            $path = getenv('PWD');
        }

        $filename = $path . '/request.http';

        $flags = 0;

        // Append request to file if it already exists, using ### as separator
        // which is understood by PhpStorm
        if (file_exists($filename)) {
            $httpRequest =  "\n\n### " . date(DATE_RFC822) . "\n\n" . $httpRequest;
            $flags = FILE_APPEND;
        }

        file_put_contents($filename, $httpRequest, $flags);

        return $filename;
    }
}

if (!function_exists('response_to_string')) {
    /**
     * Serializes a Request (PSR7 or Symfony Http Foundation) into http format
     *
     * @param Psr\Http\Message\ResponseInterface|Symfony\Component\HttpFoundation\Response $response
     * @return string
     */
    function response_to_string($response)
    {
        $responseString = "unknown\r\n";

        if (
            class_exists('Symfony\Component\HttpFoundation\Response') &&
            $response instanceof Symfony\Component\HttpFoundation\Response
        ) {
            $responseString = $response->__toString();
        }

        if (
            interface_exists('Psr\Http\Message\ResponseInterface') &&
            $response instanceof Psr\Http\Message\ResponseInterface
        ) {
            $responseString = ResponseSerializer::toString($response);
        }

        return $responseString;
    }
}

if (!function_exists('response_to_file')) {
    /**
     * Serializes a Request (PSR7 or Symfony Http Foundation) into http format and writes it into a file.
     * If the file already exists, the string will be appended, using PhpStorm .http formats ###
     * as separator
     *
     * @param Psr\Http\Message\RequestInterface|Symfony\Component\HttpFoundation\Request $request
     * @param string|null $path
     * @return string
     */
    function response_to_file($response, $path = null)
    {
        $httpResponse = response_to_string($response);

        if (empty($path)) {
            $path = $_SERVER['DOCUMENT_ROOT'];
        }

        if (empty($path)) {
            $path = getenv('PWD');
        }

        $filename = $path . '/response.http';

        $flags = 0;

        // Append request to file if it already exists, using ### as separator
        // which is understood by PhpStorm
        if (file_exists($filename)) {
            $httpResponse =  "\n\n### " . date(DATE_RFC822) . "\n\n" . $httpResponse;
            $flags = FILE_APPEND;
        }

        file_put_contents($filename, $httpResponse, $flags);

        return $filename;
    }
}
