<?php

namespace Cainy\Dockhand;

use Cainy\Dockhand\Actions\ManagesRegistry;
use GuzzleHttp\Client as HttpClient;

class Dockhand
{
    use ManagesRegistry,
        MakesHttpRequests;

    /**
     * The base URL of the registry.
     *
     * @var string
     */
    protected string $baseUrl;

    /**
     * The Guzzle HTTP Client instance.
     *
     * @var HttpClient
     */
    public HttpClient $guzzle;

    /**
     * Number of seconds a request is retried.
     *
     * @var int
     */
    public int $timeout = 30;

    /**
     * Create a new Dockhand instance.
     *
     * @return void
     */
    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;

        $this->guzzle = new HttpClient([
            'base_uri' => $baseUrl,
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'Laravel Dockhand PHP/1.0',
            ],
        ]);
    }

    /**
     * Transform the items of the collection to the given class.
     *
     * @param array $collection
     * @param  string  $class
     * @param array $extraData
     * @return array
     */
    protected function transformCollection(array $collection, string $class, array $extraData = []): array
    {
        return array_map(function ($data) use ($class, $extraData) {
            return new $class($data + $extraData, $this);
        }, $collection);
    }

    /**
     * Set the base url and set up the guzzle request object.
     *
     * @param string $baseUrl
     * @param HttpClient|null $guzzle
     * @return $this
     */
    public function setBaseUrl(string $baseUrl, HttpClient|null $guzzle = null): static
    {
        $this->baseUrl = $baseUrl;

        $this->guzzle = new HttpClient([
            'base_uri' => $baseUrl,
            'http_errors' => false,
            'headers' => [
                'Authorization' => 'Bearer ???',
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'Laravel Dockhand PHP/1.0',
            ],
        ]);

        return $this;
    }

    /**
     * Set a new timeout.
     *
     * @param int $timeout
     * @return $this
     */
    public function setTimeout(int $timeout): static
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Get the timeout.
     *
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }
}
