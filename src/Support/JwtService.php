<?php

namespace Cainy\Dockhand\Support;

use Base32\Base32;
use Closure;
use Exception;
use Illuminate\Support\Facades\Log;
use Lcobucci\Clock\FrozenClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Ecdsa\Sha256 as ES256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use Lcobucci\JWT\Validation\Validator;
use Ramsey\Uuid\Uuid;

class JwtService
{
    private Configuration $config;

    private string $kid;

    private InMemory $publicKey;

    private InMemory $privateKey;

    public function __construct()
    {
        $signer = new ES256;

        $this->privateKey = InMemory::file(config('oci.jwt_private_key'));
        $this->publicKey = InMemory::file(config('oci.jwt_public_key'));

        $this->kid = self::generateKeyId($this->privateKey);

        $this->config = Configuration::forAsymmetricSigner(
            $signer,
            $this->privateKey,
            $this->publicKey
        );
    }

    /**
     * Generate the key id (kid), which is used by the registry
     * to identify which public key to use for verification.
     */
    private static function generateKeyId(InMemory $privateKey): string
    {
        // Extract public key from private key
        $privateKey = openssl_pkey_get_private($privateKey->contents());
        $publicKeyContent = openssl_pkey_get_details($privateKey)['key'];

        // Clean pem header and footer
        $pattern = '/-----BEGIN [^-]*-----\r?\n?|-----END [^-]*-----\r?\n?/';
        $cleanedPem = trim(preg_replace($pattern, '', $publicKeyContent));

        // Convert to der
        $der = base64_decode(preg_replace('/\s+/', '', $cleanedPem));

        // Calculate digest
        $algorithm = hash_init('sha256');
        hash_update($algorithm, $der);
        $digest = hash_final($algorithm, true);

        // Shorten digest to 30 bytes
        $digest = substr($digest, 0, 30);

        // Use Base32\Base32 to encode digest
        $source = Base32::encode($digest);
        $source = str_replace('=', '', $source);

        // Format with :
        $result = [];
        for ($i = 0; $i < strlen($source); $i += 4) {
            $result[] = substr($source, $i, 4);
        }

        return implode(':', $result);
    }

    /**
     * Create a general JWT token for use in the application.
     */
    public function createToken(Closure $closure): UnencryptedToken
    {
        $builder = $this->config->builder();

        $builder = $builder
            ->issuedAt(now()->toDateTimeImmutable())
            ->canOnlyBeUsedAfter(now()->toDateTimeImmutable())
            ->identifiedBy(Uuid::uuid4()->toString());

        return $closure($builder)->getToken($this->config->signer(), $this->config->signingKey());
    }

    /**
     * Create a JWT token that can be used to authenticate at the registry.
     *
     * @throws Exception
     */
    public function createRegistryToken(Closure $closure): UnencryptedToken
    {
        $builder = $this->config->builder();

        $builder = $builder
            ->issuedAt(now()->toDateTimeImmutable())
            ->canOnlyBeUsedAfter(now()->toDateTimeImmutable())
            ->expiresAt(now()->addMinutes(5)->toDateTimeImmutable())
            ->identifiedBy(Uuid::uuid4()->toString())
            ->withHeader('kid', $this->kid);

        return $closure($builder)->getToken($this->config->signer(), $this->config->signingKey());
    }

    /**
     * Validate a JWT token that was issued by this service.
     */
    public function validateToken(string $token, Closure $closure): bool
    {
        // Parse token
        $parser = new Parser(new JoseEncoder);
        $token = $parser->parse($token);

        // Validate token
        $validator = new Validator;

        try {
            $closure($validator, $token);
            $validator->assert($token, new SignedWith($this->config->signer(), $this->config->verificationKey()));
            $validator->assert($token, new LooseValidAt(new FrozenClock(now()->toDateTimeImmutable())));

            return true;
        } catch (RequiredConstraintsViolated $e) {
            foreach ($e->violations() as $v) {
                Log::channel('stderr')->info($v);
            }

            return false;
        }
    }
}
