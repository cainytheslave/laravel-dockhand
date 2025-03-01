<?php

namespace Cainy\Dockhand\Resources;

class Layer
{
    /**
     * The name of the repository this layer is part of.
     */
    protected string $repository {
        get {
        return $this->repository;
    }
    }

    /**
     * The reference of the image manifest this layer
     * is part of. Either a tag or a digest.
     */
    protected string $reference {
        get {
        return $this->reference;
    }
    }

    /**
     * The media type of the image manifest this layer
     * is part of.
     */
    protected MediaType $mediaType {
        get {
        return $this->mediaType;
    }
    }

    /**
     * Size of the layer.
     */
    protected int $size {
        get {
        return $this->size;
    }
    }

    /**
     * Content digest identifier, typically a sha256 hash.
     */
    protected string $digest {
        get {
        return $this->digest;
    }
    }

    /**
     * Get the digest of the layer as a string.
     */
    public function __toString(): string
    {
        return $this->digest;
    }

    /**
     * Get the digest of the repository.
     */
    public function toString(): string
    {
        return $this->digest;
    }

    /**
     * Create a new layer instance.
     */
    public function __construct(string $repository, string $reference, MediaType $mediaType, int $size, string $digest)
    {
        $this->repository = $repository;
        $this->reference = $reference;
        $this->mediaType = $mediaType;
        $this->size = $size;
        $this->digest = $digest;
    }
}
