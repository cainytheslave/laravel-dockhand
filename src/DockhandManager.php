<?php

namespace Cainy\Dockhand;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Traits\ForwardsCalls;

/**
 * @mixin Dockhand
 */
class DockhandManager
{
    use ForwardsCalls;

    /**
     * The Dockhand instance.
     *
     * @var Dockhand
     */
    protected Dockhand $dockhand;

    /**
     * Create a new DockhandManager instance.
     *
     * @param string $token
     * @param HttpClient|null $guzzle
     */
    public function __construct(string $token, ?HttpClient $guzzle = null)
    {
        $this->dockhand = new Dockhand($token, $guzzle);
    }

    /**
     * Dynamically pass methods to the Dockhand instance.
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        return $this->forwardCallTo($this->dockhand, $method, $parameters);
    }
}
