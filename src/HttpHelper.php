<?php

use Zend\Diactoros\Request\Serializer as RequestSerializer;

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
