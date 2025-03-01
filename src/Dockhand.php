<?php

namespace Cainy\Dockhand;

use Cainy\Dockhand\Actions\ManagesImageManifests;
use Cainy\Dockhand\Actions\ManagesRegistry;
use Cainy\Dockhand\Actions\ManagesRepositories;
use Cainy\Dockhand\Services\RegistryRequestService as HttpClient;
use Illuminate\Http\Client\PendingRequest;

class Dockhand
{
    use ManagesRegistry,
        ManagesRepositories,
        ManagesImageManifests;

    /**
     * The base URL of the registry.
     *
     * @var string
     */
    protected string $baseUrl;

    /**
     * The HTTP Client to communicate with the registry instance.
     *
     * @var HttpClient
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
     *
     * @return PendingRequest
     */
    public function request(): PendingRequest
    {
        return $this->http->request();
    }

    /**
     * Transform the items of the collection to the given class.
     *
     * @param array $collection
     * @param string $class
     * @param array $extraData
     * @return array
     */
    protected function transformCollection(array $collection, string $class, array $extraData = []): array
    {
        return array_map(function ($data) use ($class, $extraData) {
            return new $class($data + $extraData, $this);
        }, $collection);
    }
}
