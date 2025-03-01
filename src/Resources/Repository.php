<?php

namespace Cainy\Dockhand\Resources;

use Cainy\Dockhand\Facades\Dockhand;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Collection;

class Repository
{
    public string $name;

    /**
     * Create a new repository instance.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the name of the repository.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * Get the name of the repository.
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->name;
    }

    /**
     * Get a list of all the tags in the repository.
     *
     * @throws ConnectionException
     */
    public function getTags(): Collection
    {
        return Dockhand::getTagsOfRepository($this->name);
    }
}
