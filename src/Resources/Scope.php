<?php

namespace Cainy\Dockhand\Resources;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

/**
 * Manages Docker registry access scopes for authentication.
 *
 * @method static static repository(string $repository, array $actions = []) Add repository access with custom actions.
 * @method static static pull() Configure the latest repository entry to allow pull access.
 * @method static static push() Configure the latest repository entry to allow push access.
 * @method static static pushAndPull() Configure the latest repository entry to allow both push and pull access.
 * @method static static pullAndPush() Configure the latest repository entry to allow both pull and push access.
 * @method static static delete() Set the latest repository entry to allow deletion.
 * @method static static fullRepository(string $repository) Add a full repository with pull, push, and delete access.
 * @method static static readRepository(string $repository) Add a read-only repository.
 * @method static static writeRepository(string $repository) Add a write-only repository.
 * @method static static image(string $namespace, string $repository, array $actions = []) Add an image repository with custom actions.
 * @method static static pullImage(string $namespace, string $repository) Add pull access for an image.
 * @method static static pushImage(string $namespace, string $repository) Add push access for an image.
 * @method static static fullImage(string $namespace, string $repository) Add full access for an image.
 * @method static static catalog(array $actions = ['*']) Add catalog access.
 * @method static array get() Get the access as an array.
 * @method static array|null getAt(int $index) Get the access at a specific index.
 * @method static bool isEmpty() Check if the scope is empty.
 * @method static string toString() Get the scope as a string compatible with Docker registry auth.
 * @method static array toArray() Get all access entries as an array.
 * @method static string toJson(int $options = 0) Get all access entries as JSON.
 */
class Scope implements Arrayable, JsonSerializable
{
    /**
     * The registry access entries.
     *
     * @var array
     */
    protected array $access;

    /**
     * The pull action.
     *
     * @var string
     */
    final public const string PULL = 'pull';

    /**
     * The push action.
     *
     * @var string
     */
    final public const string PUSH = 'push';

    /**
     * The delete action.
     *
     * @var string
     */
    final public const string DELETE = 'delete';

    /**
     * Allows all actions.
     *
     * @var string
     */
    final public const string ALL = '*';

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
     * Allow static method calls for better api.
     *
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
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
     * @param string $repository
     * @param array $actions
     * @return static
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
     * @return static
     */
    public function pull(): static
    {
        if (empty($this->access)) {
            return $this;
        }

        $lastIndex = count($this->access) - 1;
        $actions = $this->access[$lastIndex]['actions'];

        if (!in_array(static::PULL, $actions)) {
            $this->access[$lastIndex]['actions'][] = static::PULL;
        }

        return $this;
    }

    /**
     * Configure the latest repository entry to allow push access.
     *
     * @return static
     */
    public function push(): static
    {
        if (empty($this->access)) {
            return $this;
        }

        $lastIndex = count($this->access) - 1;
        $actions = $this->access[$lastIndex]['actions'];

        if (!in_array(static::PUSH, $actions)) {
            $this->access[$lastIndex]['actions'][] = static::PUSH;
        }

        return $this;
    }

    /**
     * Configure the latest repository entry to allow both push and pull access.
     *
     * @return static
     */
    public function pushAndPull(): static
    {
        return $this->push()->pull();
    }

    /**
     * Configure the latest repository entry to allow both pull and push access.
     *
     * @return static
     */
    public function pullAndPush(): static
    {
        return $this->pushAndPull();
    }

    /**
     * Set the latest repository entry to allow deletion.
     *
     * @return static
     */
    public function delete(): static
    {
        if (empty($this->access)) {
            return $this;
        }

        $lastIndex = count($this->access) - 1;
        $actions = $this->access[$lastIndex]['actions'];

        if (!in_array(static::DELETE, $actions)) {
            $this->access[$lastIndex]['actions'][] = static::DELETE;
        }

        return $this;
    }

    /**
     * Add a full repository with pull, push, and delete access.
     *
     * @param string $repository
     * @return static
     */
    public function fullRepository(string $repository): static
    {
        return $this->repository($repository)->pushAndPull()->delete();
    }

    /**
     * Add a read-only repository.
     *
     * @param string $repository
     * @return static
     */
    public function readRepository(string $repository): static
    {
        return $this->repository($repository)->pull();
    }

    /**
     * Add a write-only repository.
     *
     * @param string $repository
     * @return static
     */
    public function writeRepository(string $repository): static
    {
        return $this->repository($repository)->push();
    }

    /**
     * Add an image repository with custom actions.
     *
     * @param string $namespace
     * @param string $repository
     * @param array $actions
     * @return static
     */
    public function image(string $namespace, string $repository, array $actions = []): static
    {
        return $this->repository("$namespace/$repository", $actions);
    }

    /**
     * Add pull access for an image.
     *
     * @param string $namespace
     * @param string $repository
     * @return static
     */
    public function pullImage(string $namespace, string $repository): static
    {
        return $this->image($namespace, $repository)->pull();
    }

    /**
     * Add push access for an image.
     *
     * @param string $namespace
     * @param string $repository
     * @return static
     */
    public function pushImage(string $namespace, string $repository): static
    {
        return $this->image($namespace, $repository)->push();
    }

    /**
     * Add full access for an image.
     *
     * @param string $namespace
     * @param string $repository
     * @return static
     */
    public function fullImage(string $namespace, string $repository): static
    {
        return $this->image($namespace, $repository)->pushAndPull()->delete();
    }

    /**
     * Add catalog access.
     *
     * @param array $actions
     * @return static
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
}
