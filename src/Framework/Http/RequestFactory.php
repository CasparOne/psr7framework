<?php


namespace Framework\Http;


class RequestFactory
{
    public static function fromGlobals(array $qurey = [], array $body = null) : Request
    {
        return (new Request())
            ->withQueryParams($qurey ?: $_GET)
            ->withParsedBody($body ?: $_POST);
    }

}