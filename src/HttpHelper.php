<?php

use Zend\Diactoros\Request\Serializer as RequestSerializer;

if (!function_exists('request_to_string')) {
    function request_to_string($request)
    {
        return RequestSerializer::toString($request);
    }
}

if (!function_exists('request_to_curl')) {
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
    function request_to_file($request, $path = null)
    {
        $httpRequest = request_to_string($request);

        if (empty($path)) {
            $path = $_SERVER['DOCUMENT_ROOT'];
        }

        $filename = $path . '/request.http';

        file_put_contents($filename, $httpRequest);

        return $filename;
    }
}
