<?php

namespace Cainy\Dockhand\Exceptions;

use Exception;

class UnknownException extends Exception
{
    public function __construct(string $code, string $message)
    {
        parent::__construct("Unknown Exception '$code': $message");
    }
}
