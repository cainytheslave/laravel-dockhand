<?php

namespace Cainy\Vessel\Exceptions;

use Exception;

class BlobUploadInvalidException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
