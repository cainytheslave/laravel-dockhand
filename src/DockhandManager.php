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
     * The LaravelOci instance.
     *
     * @var Dockhand
     */
    protected Dockhand $oci;

    /**
     * Create a new LaravelOciManager instance.
     *
     * @param string $token
     * @param HttpClient|null $guzzle
     */
    public function __construct(string $token, ?HttpClient $guzzle = null)
    {
        $this->oci = new Dockhand($token, $guzzle);
    }

    /**
     * Dynamically pass methods to the LaravelOci instance.
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        return $this->forwardCallTo($this->oci, $method, $parameters);
    }
}
