<?php

use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Request\Serializer as RequestSerializer;

function requestToString(RequestInterface $request)
{
    return RequestSerializer::toString($request);
}

function requestToCurl(RequestInterface $request)
{
    $curl = sprintf('curl -X %s %s', $request->getMethod(), $request->getUri());

    foreach ($request->getHeaders() as $name => $values) {
        $curl .= sprintf(" -H '%s'", $name . ": " . implode(", ", $values));
    }

    $body = (string) $request->getBody();

    if ($body) {
        $curl .= sprintf(" -d '%s'", $body);
    }

    return $curl;
}

function requestToFile(RequestInterface $request, $path = null)
{
    $httpRequest = RequestSerializer::toString($request);

    if (empty($path)) {
        $path = $_SERVER['DOCUMENT_ROOT'];
    }

    $filename = $path . '/request.http';

    file_put_contents($filename, $httpRequest);

    return $filename;
}
