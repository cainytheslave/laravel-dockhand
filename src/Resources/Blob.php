<?php

namespace Cainy\Dockhand\Resources;

use Illuminate\Support\Facades\Date;

class Blob
{
    public string $digest;

    public int $size;

    public MediaType $mediaType;

    public string $annotations;

    public Date $createdAt;

    public Date $updatedAt;
}
