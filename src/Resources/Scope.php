<?php

namespace Cainy\Dockhand\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Traits\Macroable;
use JsonSerializable;

class Scope implements Arrayable, JsonSerializable
{
    /**
     * The registry access entries.
     *
     * @var array
     */
    protected array $access;

    /**
     * Create a new scope instance.
     *
     * @return void
     */
    final public function __construct()
    {
        $this->access = [];
    }

    /**
     * Create a new scope instance.
     *
     * @return static
     */
    public static function create(): static
    {
        return new static;
    }

    /**
     * Add repository access with custom actions.
     *
     * @param  string  $repository
     * @param  array   $actions
     * @return $this
     */
    public function repository(string $repository, array $actions = []): static
    {
        $this->access[] = [
            'type' => 'repository',
            'name' => $repository,
            'actions' => $actions,
        ];

        return $this;
    }

    /**
     * Configure the latest repository entry to allow pull access.
     *
     * @return $this
     */
    public function pull(): static
    {
        if (empty($this->access)) {
            return $this;
        }

        $lastIndex = count($this->access) - 1;
        $actions = $this->access[$lastIndex]['actions'];

        if (!in_array('pull', $actions)) {
            $this->access[$lastIndex]['actions'][] = 'pull';
        }

        return $this;
    }

    /**
     * Configure the latest repository entry to allow push access.
     *
     * @return $this
     */
    public function push(): static
    {
        if (empty($this->access)) {
            return $this;
        }

        $lastIndex = count($this->access) - 1;
        $actions = $this->access[$lastIndex]['actions'];

        if (!in_array('push', $actions)) {
            $this->access[$lastIndex]['actions'][] = 'push';
        }

        return $this;
    }

    /**
     * Configure the latest repository entry to allow both push and pull access.
     *
     * @return $this
     */
    public function pushAndPull(): static
    {
        return $this->push()->pull();
    }

    /**
     * Configure the latest repository entry to allow both pull and push access.
     *
     * @return $this
     */
    public function pullAndPush(): static
    {
        return $this->pushAndPull();
    }

    /**
     * Set the latest repository entry to allow deletion.
     *
     * @return $this
     */
    public function delete(): static
    {
        if (empty($this->access)) {
            return $this;
        }

        $lastIndex = count($this->access) - 1;
        $actions = $this->access[$lastIndex]['actions'];

        if (!in_array('delete', $actions)) {
            $this->access[$lastIndex]['actions'][] = 'delete';
        }

        return $this;
    }

    /**
     * Add a full repository with pull, push, and delete access.
     *
     * @param  string  $repository
     * @return $this
     */
    public function fullRepository(string $repository): static
    {
        return $this->repository($repository)->pushAndPull()->delete();
    }

    /**
     * Add a read-only repository.
     *
     * @param  string  $repository
     * @return $this
     */
    public function readRepository(string $repository): static
    {
        return $this->repository($repository)->pull();
    }

    /**
     * Add a write-only repository.
     *
     * @param  string  $repository
     * @return $this
     */
    public function writeRepository(string $repository): static
    {
        return $this->repository($repository)->push();
    }

    /**
     * Add an image repository with custom actions.
     *
     * @param  string  $namespace
     * @param  string  $repository
     * @param  array   $actions
     * @return $this
     */
    public function image(string $namespace, string $repository, array $actions = []): static
    {
        return $this->repository("$namespace/$repository", $actions);
    }

    /**
     * Add pull access for an image.
     *
     * @param  string  $namespace
     * @param  string  $repository
     * @return $this
     */
    public function pullImage(string $namespace, string $repository): static
    {
        return $this->image($namespace, $repository)->pull();
    }

    /**
     * Add push access for an image.
     *
     * @param  string  $namespace
     * @param  string  $repository
     * @return $this
     */
    public function pushImage(string $namespace, string $repository): static
    {
        return $this->image($namespace, $repository)->push();
    }

    /**
     * Add full access for an image.
     *
     * @param  string  $namespace
     * @param  string  $repository
     * @return $this
     */
    public function fullImage(string $namespace, string $repository): static
    {
        return $this->image($namespace, $repository)->pushAndPull()->delete();
    }

    /**
     * Add catalog access.
     *
     * @param  array  $actions
     * @return $this
     */
    public function catalog(array $actions = ['*']): static
    {
        $this->access[] = [
            'type' => 'registry',
            'name' => 'catalog',
            'actions' => $actions,
        ];

        return $this;
    }

    /**
     * Add namespace access with specific actions.
     *
     * @param  string  $namespace
     * @param  array   $actions
     * @return $this
     */
    public function namespace(string $namespace, array $actions = []): static
    {
        // For registry implementations that support namespace level permissions
        return $this->repository("$namespace/*", $actions);
    }

    /**
     * Get the access as an array.
     *
     * @return array
     */
    public function get(): array
    {
        return $this->access;
    }

    /**
     * Get the access at a specific index.
     *
     * @param int $index
     * @return array|null
     */
    public function getAt(int $index): ?array
    {
        return $this->access[$index] ?? null;
    }

    /**
     * Check if the scope is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->access);
    }

    /**
     * Get the scope as a string compatible with Docker registry auth.
     *
     * @return string
     */
    public function toString(): string
    {
        $parts = [];

        foreach ($this->access as $entry) {
            $actions = implode(',', $entry['actions']);
            $parts[] = "{$entry['type']}:{$entry['name']}:{$actions}";
        }

        return implode(' ', $parts);
    }

    /**
     * Get the string representation of the scope.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Get all access entries as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->access;
    }

    /**
     * Get all access entries as JSON.
     *
     * @param int $options
     * @return string
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->access, $options);
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public static function __callStatic($method, $parameters) {
        return (new Scope)->$method(...$parameters);
    }
}
