<?php

namespace Cainy\Dockhand\Events;

use Cainy\Dockhand\Resources\MediaType;

abstract class RegistryEvent extends RegistryBaseEvent
{
    protected int $targetSize;
    protected string $targetRepository;
    protected string $targetUrl;
    protected string $targetTag;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->targetMediaType = MediaType::fromString($data['target']['mediaType']);
        $this->targetSize = $data['target']['size'];
        $this->targetUrl = $data['target']['url'];
        $this->targetTag = $data['target']['tag'];
    }

}
