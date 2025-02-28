<?php

namespace Cainy\Vessel;

use Exception;

use GuzzleHttp\Exception\GuzzleException;
use Cainy\Vessel\Exceptions\BlobUnknownException;
use Cainy\Vessel\Exceptions\BlobUploadInvalidException;
use Cainy\Vessel\Exceptions\BlobUploadUnknownException;
use Cainy\Vessel\Exceptions\DeniedException;
use Cainy\Vessel\Exceptions\DigestInvalidException;
use Cainy\Vessel\Exceptions\ManifestBlobUnknownException;
use Cainy\Vessel\Exceptions\ManifestInvalidException;
use Cainy\Vessel\Exceptions\ManifestUnknownException;
use Cainy\Vessel\Exceptions\ManifestUnverifiedException;
use Cainy\Vessel\Exceptions\NameInvalidException;
use Cainy\Vessel\Exceptions\NameUnknownException;
use Cainy\Vessel\Exceptions\PaginationNumberInvalidException;
use Cainy\Vessel\Exceptions\ParseException;
use Cainy\Vessel\Exceptions\RangeInvalidException;
use Cainy\Vessel\Exceptions\SizeInvalidException;
use Cainy\Vessel\Exceptions\TagInvalidException;
use Cainy\Vessel\Exceptions\TimeoutException;
use Cainy\Vessel\Exceptions\TooManyRequestsException;
use Cainy\Vessel\Exceptions\UnauthorizedException;
use Cainy\Vessel\Exceptions\UnknownException;
use Cainy\Vessel\Exceptions\UnsupportedException;
use Psr\Http\Message\ResponseInterface;

trait MakesHttpRequests
{
    /**
     * Make a GET request to Registry and return the response.
     *
     * @param string $uri
     * @return mixed
     * @throws GuzzleException
     */
    public function get(string $uri): mixed
    {
        return $this->request('GET', $uri);
    }

    /**
     * Make a POST request to Registry and return the response.
     *
     * @param string $uri
     * @param array $payload
     * @return mixed
     * @throws GuzzleException
     */
    public function post(string $uri, array $payload = []): mixed
    {
        return $this->request('POST', $uri, $payload);
    }

    /**
     * Make a PUT request to Registry and return the response.
     *
     * @param string $uri
     * @param array $payload
     * @return mixed
     * @throws GuzzleException
     */
    public function put(string $uri, array $payload = []): mixed
    {
        return $this->request('PUT', $uri, $payload);
    }

    /**
     * Make a DELETE request to registry and return the response.
     *
     * @param string $uri
     * @param array $payload
     * @return mixed
     * @throws GuzzleException
     */
    public function delete(string $uri, array $payload = []): mixed
    {
        return $this->request('DELETE', $uri, $payload);
    }

    /**
     * Make request to registry and return the response.
     *
     * @param string $verb
     * @param string $uri
     * @param array $payload
     * @return mixed
     * @throws GuzzleException
     * @throws Exception
     */
    protected function request(string $verb, string $uri, array $payload = []): mixed
    {
        if (isset($payload['json'])) {
            $payload = ['json' => $payload['json']];
        } else {
            $payload = empty($payload) ? [] : ['form_params' => $payload];
        }

        $response = $this->guzzle->request($verb, $uri, $payload);

        $statusCode = $response->getStatusCode();

        if ($statusCode < 200 || $statusCode > 299) {
            $this->handleRequestError($response);
        } else {
            $responseBody = (string) $response->getBody();

            return json_decode($responseBody, true) ?: $responseBody;
        }
    }

    /**
     * Handle the request error.
     *
     * @param ResponseInterface $response
     * @return void
     *
     * @throws BlobUnknownException
     * @throws BlobUploadInvalidException
     * @throws BlobUploadUnknownException
     * @throws DeniedException
     * @throws DigestInvalidException
     * @throws ManifestBlobUnknownException
     * @throws ManifestInvalidException
     * @throws ManifestUnknownException
     * @throws ManifestUnverifiedException
     * @throws NameInvalidException
     * @throws NameUnknownException
     * @throws PaginationNumberInvalidException
     * @throws ParseException
     * @throws RangeInvalidException
     * @throws SizeInvalidException
     * @throws TagInvalidException
     * @throws UnauthorizedException
     * @throws UnknownException
     * @throws UnsupportedException
     * @throws TooManyRequestsException
     */
    protected function handleRequestError(ResponseInterface $response): void
    {
        $body = json_decode((string) $response->getBody());
        if (!isset($body['errors'])) {
            throw new ParseException("Expected error response to have 'errors' property.");
        }

        if (empty($errors = $body['errors'])) {
            throw new ParseException("Expected non-empty array as error property.");
        }

        $code = $errors['code'];
        $message = $errors['message'];

        throw match ($code) {
            'BLOB_UNKNOWN' => new BlobUnknownException($message),
            'BLOB_UPLOAD_INVALID' => new BlobUploadInvalidException($message),
            'BLOB_UPLOAD_UNKNOWN' => new BlobUploadUnknownException($message),
            'DIGEST_INVALID' => new DigestInvalidException($message),
            'MANIFEST_BLOB_UNKNOWN' => new ManifestBlobUnknownException($message),
            'MANIFEST_INVALID' => new ManifestInvalidException($message),
            'MANIFEST_UNKNOWN' => new ManifestUnknownException($message),
            'MANIFEST_UNVERIFIED' => new ManifestUnverifiedException($message),
            'NAME_INVALID' => new NameInvalidException($message),
            'NAME_UNKNOWN' => new NameUnknownException($message),
            'PAGINATION_NUMBER_INVALID' => new PaginationNumberInvalidException($message),
            'RANGE_INVALID' => new RangeInvalidException($message),
            'SIZE_INVALID' => new SizeInvalidException($message),
            'TAG_INVALID' => new TagInvalidException($message),
            'UNAUTHORIZED' => new UnauthorizedException($message),
            'DENIED' => new DeniedException($message),
            'UNSUPPORTED' => new UnsupportedException($message),
            'TOOMANYREQUESTS' => new TooManyRequestsException($message),
            default => new UnknownException($code, $message),
        };
    }

    /**
     * Retry the callback or fail after x seconds.
     *
     * @param int $timeout
     * @param callable $callback
     * @param int $sleep
     * @return mixed
     *
     * @throws TimeoutException
     */
    public function retry(int $timeout, callable $callback, int $sleep = 5): mixed
    {
        $start = time();

        beginning:

        if ($output = $callback()) {
            return $output;
        }

        if (time() - $start < $timeout) {
            sleep($sleep);

            goto beginning;
        }

        if ($output === null || $output === false) {
            $output = [];
        }

        if (! is_array($output)) {
            $output = [$output];
        }

        throw new TimeoutException($output);
    }
}
