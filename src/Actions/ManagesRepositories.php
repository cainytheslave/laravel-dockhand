<?php

namespace Cainy\Dockhand\Actions;

use Cainy\Dockhand\Facades\Dockhand;
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
     */
    public function getRepository(string $repository): Repository
    {
        return new Repository($repository);
    }

    /**
     * Get a list of all the tags in the repository.
     *
     * @throws ConnectionException
     */
    public function getTagsOfRepository(string $repository): Collection
    {
        return collect(Dockhand::request()
            ->withToken(Token::withScope(Scope::readRepository($repository)))
            ->get("/$repository/tags/list")['tags'])
            ->map(fn ($tag) => new Tag($repository, $tag));
    }
}
