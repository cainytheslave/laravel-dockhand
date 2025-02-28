<?php

namespace Cainy\Vessel\Resources;

use Illuminate\Support\Facades\Date;

class ImageManifest
{
    public string $digest;

    public MediaType $mediaType;

    public array $config;

    public array $layers;

    public string $annotations;

    public Date $createdAt;

    public Date $updatedAt;
}
