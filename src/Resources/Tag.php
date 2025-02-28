<?php

namespace Cainy\Dockhand\Resources;

use Illuminate\Support\Facades\Date;

class Tag
{
    public string $name;

    public ImageManifest $imageManifest;

    public Date $createdAt;

    public Date $updatedAt;
}
