<?php

namespace Cainy\Dockhand\Resources;

use Cainy\Dockhand\Facades\TokenService;
use Illuminate\Support\Carbon;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\UnencryptedToken;

/**
 * Token builder and general helper class. Offers a simple way
 * to add scopes (needed for registry) to the tokens.
 * Able to generate {@see UnencryptedToken}.
 *
 * @method static static issuedBy(string $issuer) Set the issuer (iss) claim.
 * @method static static permittedFor(string $audience) Set the audience (aud) claim.
 * @method static static expiresAt(Carbon $time) Set the expiration time (exp) claim.
 * @method static static canOnlyBeUsedAfter(Carbon $time) Set the "not before" (nbf) claim.
 * @method static static withClaim(string $name, mixed $value) Add a custom claim.
 * @method static static withScope(Scope $scope) Add a registry scope to the token.
 * @method static static withHeader(string $name, mixed $value) Add a custom header.
 * @method static UnencryptedToken sign() Generate and return the signed JWT token.
 * @method static UnencryptedToken get() Generate and return the signed JWT token.
 * @method static string toString() Generate and return the signed JWT token as a string.
 */
class Token
{
    protected Builder $builder;

    protected array $access;

    /**
     * Create a new TokenBuilder instance.
     *
     * @param  Builder|null  $builder  Optional custom builder instance.
     */
    final public function __construct(?Builder $builder = null)
    {
        $this->builder = $builder ?: TokenService::getBuilder();
        $this->access = [];

        if (auth()->check()) {
            $this->builder =
                $this->builder->relatedTo(auth()->user()->getAuthIdentifierName());
        }
    }

    /**
     * Allow static method calls for better api.
     */
    public static function __callStatic(string $method, array $parameters): mixed
    {
        return (new static)->$method(...$parameters);
    }

    /**
     * Generate and return the signed JWT token as string.
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Set the issuer (iss) claim.
     */
    public function issuedBy(string $issuer): static
    {
        $this->builder = $this->builder->issuedBy($issuer);

        return $this;
    }

    /**
     * Set the audience (aud) claim.
     */
    public function permittedFor(string $audience): static
    {
        $this->builder = $this->builder->permittedFor($audience);

        return $this;
    }

    /**
     * Set the expiration time (exp) claim.
     */
    public function expiresAt(Carbon $time): static
    {
        $this->builder = $this->builder->expiresAt($time->toDateTimeImmutable());

        return $this;
    }

    /**
     * Set the "not before" (nbf) claim.
     */
    public function canOnlyBeUsedAfter(Carbon $time): static
    {
        $this->builder = $this->builder->canOnlyBeUsedAfter($time->toDateTimeImmutable());

        return $this;
    }

    /**
     * Add a custom claim.
     */
    public function withClaim(string $name, mixed $value): static
    {
        if ($name == 'access') {
            $this->access[] = $value;
        } else {
            $this->builder = $this->builder->withClaim($name, $value);
        }

        return $this;
    }

    /**
     * Add a registry scope to the token.
     */
    public function withScope(Scope $scope): static
    {
        $this->access = array_combine($this->access, $scope->toArray());

        return $this;
    }

    /**
     * Add a custom header.
     */
    public function withHeader(string $name, mixed $value): static
    {
        $this->builder = $this->builder->withHeader($name, $value);

        return $this;
    }

    /**
     * Generate and return the signed JWT token.
     */
    public function sign(): UnencryptedToken
    {
        $this->builder = $this->builder->withClaim('access', $this->access);

        return TokenService::signToken($this->builder);
    }

    /**
     * Generate and return the signed JWT token.
     */
    public function get(): UnencryptedToken
    {
        return $this->sign();
    }

    /**
     * Generate and return the signed JWT token as string.
     */
    public function toString(): string
    {
        return $this->sign()->toString();
    }
}
