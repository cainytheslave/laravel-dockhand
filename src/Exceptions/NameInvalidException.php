<?php

namespace Cainy\Dockhand\Exceptions;

use Exception;

class NameInvalidException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
