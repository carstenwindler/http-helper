<?php

use Laminas\Diactoros\Request\Serializer as RequestSerializer;
use Laminas\Diactoros\Response\Serializer as ResponseSerializer;

if (!function_exists('request_to_string')) {
    /**
     * Serializes a Request (PSR7 or Symfony Http Foundation) into http format
     *
     * @param Psr\Http\Message\RequestInterface|Symfony\Component\HttpFoundation\Request $request
     * @return string
     */
    function request_to_string($request): string
    {
        $requestString = "unknown\r\n";

        if ($request instanceof Symfony\Component\HttpFoundation\Request &&
            class_exists(Symfony\Component\HttpFoundation\Request::class)
        ) {
            $requestString = $request->__toString();
        }

        if ($request instanceof Psr\Http\Message\RequestInterface &&
            interface_exists(Psr\Http\Message\RequestInterface::class)
        ) {
            $requestString = RequestSerializer::toString($request);
        }

        return $requestString;
    }
}

if (!function_exists('request_to_curl')) {
    /**
     * Serializes a Request (PSR7 or Symfony Http Foundation) into curl syntax
     *
     * @param Psr\Http\Message\RequestInterface|Symfony\Component\HttpFoundation\Request $request
     * @return string
     */
    function request_to_curl($request): string
    {
        $curl = sprintf('curl -X %s %s', $request->getMethod(), $request->getUri());

        foreach ($request->getHeaders() as $name => $values) {
            $curl .= sprintf(" -H '%s'", $name . ': ' . implode(', ', $values));
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
    function request_to_file($request, string $path = null): string
    {
        $httpRequest = request_to_string($request);

        if (empty($path)) {
            $path = (string) $_SERVER['DOCUMENT_ROOT'];
        }

        if (empty($path)) {
            $path = (string) getenv('PWD');
        }

        $filename = $path . '/request.http';

        $flags = 0;

        // Append request to file if it already exists, using ### as separator
        // which is understood by PhpStorm
        if (file_exists($filename)) {
            $httpRequest = "\n\n### " . date(DATE_RFC822) . "\n\n" . $httpRequest;
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
    function response_to_string($response): string
    {
        $responseString = "unknown\r\n";

        if ($response instanceof Symfony\Component\HttpFoundation\Response &&
            class_exists(Symfony\Component\HttpFoundation\Response::class)
        ) {
            $responseString = $response->__toString();
        }

        if ($response instanceof Psr\Http\Message\ResponseInterface &&
            interface_exists(Psr\Http\Message\ResponseInterface::class)
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
     * @param Psr\Http\Message\ResponseInterface|Symfony\Component\HttpFoundation\Response $response
     * @param string|null $path
     * @return string
     */
    function response_to_file($response, string $path = null): string
    {
        $httpResponse = response_to_string($response);

        if (empty($path)) {
            $path = (string) $_SERVER['DOCUMENT_ROOT'];
        }

        if (empty($path)) {
            $path = (string) getenv('PWD');
        }

        $filename = $path . '/response.http';

        $flags = 0;

        // Append request to file if it already exists, using ### as separator
        // which is understood by PhpStorm
        if (file_exists($filename)) {
            $httpResponse = "\n\n### " . date(DATE_RFC822) . "\n\n" . $httpResponse;
            $flags = FILE_APPEND;
        }

        file_put_contents($filename, $httpResponse, $flags);

        return $filename;
    }
}
