<?php

namespace Cainy\Dockhand\Events;

use Cainy\Dockhand\Resources\MediaType;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

abstract class RegistryBaseEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected string $id;
    protected Carbon $timestamp;
    protected EventAction $action;
    protected MediaType $targetMediaType;
    protected string $targetDigest;
    protected string $targetRepository;
    protected string $requestId;
    protected string $requestAddr;
    protected string $requestHost;
    protected string $requestMethod;
    protected string $requestUserAgent;
    protected string $actorName;
    protected string $sourceAddr;
    protected string $sourceInstanceId;

    /**
     * Base constructor for all registry events.
     *
     * @param array $data Raw notification data from the registry webhook.
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->timestamp = Carbon::parse($data['timestamp']);
        $this->action = EventAction::from($data['action']);

        $this->targetDigest = $data['target']['digest'];
        $this->targetRepository = $data['target']['repository'];

        $this->requestId = $data['request']['id'];
        $this->requestAddr = $data['request']['addr'];
        $this->requestHost = $data['request']['host'];
        $this->requestMethod = $data['request']['method'];
        $this->requestUserAgent = $data['request']['useragent'];

        $this->actorName = $data['actor']['name'];

        $this->sourceAddr = $data['source']['addr'];
        $this->sourceInstanceId = $data['source']['instanceID'];
    }

}
