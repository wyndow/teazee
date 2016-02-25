<?php

namespace Teazee\Exception;

use LogicException;

class ServiceMissingException extends LogicException
{
    public static function noHttpClient()
    {
        return new self('No HttpClient provided and no discovery service is present to guess it, maybe you need to install the php-http/discovery package?');
    }

    public static function noMessageFactory()
    {
        return new self('No MessageFactory provided and no discovery service is present to guess it, maybe you need to install the php-http/discovery package?');
    }
}
