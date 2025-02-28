<?php

namespace Cainy\Vessel\Resources;

use Illuminate\Support\Facades\Date;

class Repository
{
    public string $name;

    public array $tags;

    public Date $createdAt;

    public Date $updatedAt;
}
