<?php

namespace Cainy\Dockhand\Actions;

use Cainy\Dockhand\Facades\Dockhand;
use Cainy\Dockhand\Resources\ImageManifest;
use Cainy\Dockhand\Resources\Layer;
use Cainy\Dockhand\Resources\MediaType;
use Cainy\Dockhand\Resources\Scope;
use Cainy\Dockhand\Resources\Token;
use Exception;
use Illuminate\Http\Client\ConnectionException;

trait ManagesTags
{
    /**
     * Get the manifest of a tag.
     *
     * @throws ConnectionException
     * @throws Exception
     */
    public function getManifestOfTag(string $repository, string $tag): ImageManifest
    {
        $data = Dockhand::request()
            ->withToken(Token::withScope(Scope::readRepository($repository)))
            ->accept(MediaType::getManifestTypesAsString())
            ->get("/$repository/manifests/$tag");

        if (isset($data['schemaVersion']) && $data['schemaVersion'] != 2) {
            throw new \Exception('Unsupported schema version');
        }

        $mediaType = MediaType::fromString($data['mediaType']);

        $config = $data['config'] ?? [];

        $layers = collect($data['layers'])
            ->map(fn ($layer) => new Layer(
                $repository,
                $layer['digest'],
                MediaType::fromString($layer['mediaType']),
                $layer['size'],
                $layer['digest']))
            ->toArray();

        return new ImageManifest($repository, $tag, $mediaType, $config, $layers);
    }
}
