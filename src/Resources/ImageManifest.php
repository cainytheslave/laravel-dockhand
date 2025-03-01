<?php

namespace Cainy\Dockhand\Resources;

use Cainy\Dockhand\Facades\Dockhand;
use Exception;
use Illuminate\Http\Client\ConnectionException;

class ImageManifest
{
    /**
     * The name of the repository.
     */
    protected string $repository;

    /**
     * The reference of the image manifest. Either a tag or a digest.
     */
    protected string $reference;

    /**
     * The media type of the image manifest.
     */
    protected MediaType $mediaType;

    /**
     * Docker container configuration object. This configuration item
     * is a JSON blob that the runtime uses to set up the container.
     *
     * @property string $mediaType The MIME type of the referenced object. Generally
     *                             application/vnd.docker.container.image.v1+json.
     * @property int $size The size in bytes of the object. Exists so that a client
     *                     will have an expected size for the content before validating.
     * @property string $digest Content digest identifier, typically a sha256 hash.
     */
    protected array $config;

    /**
     * The layers of the image.
     */
    protected array $layers;

    /**
     * Create a new image manifest instance.
     */
    public function __construct(string $repository, string $reference, MediaType $mediaType, array $config, array $layers)
    {
        $this->repository = $repository;
        $this->reference = $reference;
        $this->mediaType = $mediaType;
        $this->config = $config;
        $this->layers = $layers;
    }

    /**
     * @throws ConnectionException
     * @throws Exception
     */
    public static function fetch(string $repository, string $reference): ImageManifest
    {
        Dockhand::getManifestOfTag($repository, $reference);
    }
}
