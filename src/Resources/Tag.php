<?php

namespace Cainy\Dockhand\Resources;

use Cainy\Dockhand\Facades\Dockhand;
use Illuminate\Support\Facades\Date;

class Tag
{
    /**
     * The name of the repository this tag is part of.
     *
     * @var string
     */
    protected string $repository {
        get {
            return $this->repository;
        }
    }

    /**
     * The name of the tag.
     *
     * @var string
     */
    protected string $name {
        get {
            return $this->name;
        }
    }

    /**
     * Create a new tag instance.
     *
     * @param string $repository
     * @param string $name
     */
    public function __construct(string $repository, string $name)
    {
        $this->repository = $repository;
        $this->name = $name;
    }

    /**
     * Get the name of the tag.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * Get the name of the tag.
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->name;
    }

    /**
     * Get the manifest of the tag.
     *
     * @return ImageManifest
     */
    public function getManifest(): ImageManifest
    {
        return Dockhand::getManifestOfTag($this->repository, $this->name);
    }
}
