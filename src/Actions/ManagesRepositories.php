<?php

namespace Cainy\Dockhand\Actions;

use Cainy\Dockhand\Facades\Dockhand;
use Cainy\Dockhand\Resources\RegistryApiVersion;
use Cainy\Dockhand\Resources\Repository;
use Cainy\Dockhand\Resources\Scope;
use Cainy\Dockhand\Resources\Tag;
use Cainy\Dockhand\Resources\Token;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Collection;

trait ManagesRepositories
{
    /**
     * Get a repository by name.
     *
     * @param string $repository
     * @return Repository
     */
    function getRepository(string $repository): Repository
    {
        return new Repository($repository);
    }

    /**
     * Get a list of all the tags in the repository.
     *
     * @param string $repository
     * @return Collection
     * @throws ConnectionException
     */
    function getTagsOfRepository(string $repository): Collection
    {
        return collect(Dockhand::request()
            ->withToken(Token::withScope(Scope::readRepository($repository)))
            ->get("/$repository/tags/list")['tags'])
            ->map(fn($tag) => new Tag($repository, $tag));
    }
}
