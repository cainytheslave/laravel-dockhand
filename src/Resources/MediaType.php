<?php

namespace Cainy\Vessel\Resources;

use Illuminate\Support\Facades\Log;
use ValueError;

enum MediaType: string
{
    // Image Manifest Media Types
    case IMAGE_MANIFEST_V1 = 'application/vnd.oci.image.manifest.v1+json';
    case IMAGE_MANIFEST_V1_SIGNED = 'application/vnd.oci.image.manifest.v1+json+signed';

    // Image Index Media Types
    case IMAGE_INDEX_V1 = 'application/vnd.oci.image.index.v1+json';

    // Image Config Media Types
    case IMAGE_CONFIG_V1 = 'application/vnd.oci.image.config.v1+json';

    // Image Layer Media Types
    case IMAGE_LAYER_V1_TAR = 'application/vnd.oci.image.layer.v1.tar';
    case IMAGE_LAYER_V1_TAR_GZIP = 'application/vnd.oci.image.layer.v1.tar+gzip';
    case IMAGE_LAYER_V1_TAR_ZSTD = 'application/vnd.oci.image.layer.v1.tar+zstd';

    // Other Media Types
    case EMPTY_JSON = 'application/vnd.oci.empty.v1+json';

    // Custom Media Type
    case CUSTOM = 'custom';

    /**
     * Create a MediaType from a string.
     * If the media type is unknown, return CUSTOM.
     */
    public static function fromString(string $mediaType): self
    {
        try {
            return self::from($mediaType);
        } catch (ValueError) {
            Log::warning("Unknown media type: {$mediaType}");

            return self::CUSTOM;
        }
    }

    /**
     * Check if the media type is for an image manifest.
     */
    public function isImageManifest(): bool
    {
        return in_array($this, [
            self::IMAGE_MANIFEST_V1,
            self::IMAGE_MANIFEST_V1_SIGNED,
        ]);
    }

    /**
     * Check if the media type is for an image layer.
     */
    public function isImageLayer(): bool
    {
        return in_array($this, [
            self::IMAGE_LAYER_V1_TAR,
            self::IMAGE_LAYER_V1_TAR_GZIP,
            self::IMAGE_LAYER_V1_TAR_ZSTD,
        ]);
    }

    /**
     * Check if the media type is for an image config.
     */
    public function isImageConfig(): bool
    {
        return $this === self::IMAGE_CONFIG_V1;
    }

    /**
     * Check if the media type is for an image index.
     */
    public function isImageIndex(): bool
    {
        return $this === self::IMAGE_INDEX_V1;
    }

    /**
     * Check if the media type is custom.
     */
    public function isCustom(): bool
    {
        return $this === self::CUSTOM;
    }
}
