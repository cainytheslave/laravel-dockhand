<?php

namespace Cainy\Dockhand;

use Cainy\Dockhand\Actions\ManagesImageManifests;
use Cainy\Dockhand\Actions\ManagesRegistry;
use Cainy\Dockhand\Actions\ManagesRepositories;
use Cainy\Dockhand\Services\RegistryRequestService as HttpClient;
use Illuminate\Http\Client\PendingRequest;

class Dockhand
{
    use ManagesImageManifests,
        ManagesRegistry,
        ManagesRepositories;

    /**
     * The base URL of the registry.
     */
    protected string $baseUrl;

    /**
     * The HTTP Client to communicate with the registry instance.
     */
    protected HttpClient $http;

    /**
     * Create a new Dockhand instance.
     *
     * @return void
     */
    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
        $this->http = new HttpClient($baseUrl);
    }

    /**
     * Make a request to the registry.
     */
    public function request(): PendingRequest
    {
        return $this->http->request();
    }

    /**
     * Transform the items of the collection to the given class.
     */
    protected function transformCollection(array $collection, string $class, array $extraData = []): array
    {
        return array_map(function ($data) use ($class, $extraData) {
            return new $class($data + $extraData, $this);
        }, $collection);
    }
}
