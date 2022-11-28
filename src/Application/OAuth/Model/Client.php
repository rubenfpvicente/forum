<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Application\OAuth\Model;

use App\Domain\OAuth\Client\ClientId;
use App\Infrastructure\JsonApi\SchemaDiscovery\AsResourceObject;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attribute;
use App\Infrastructure\JsonApi\SchemaDiscovery\ResourceIdentifier;

/**
 * Client
 *
 * @package App\Application\OAuth\Model
 */
#[AsResourceObject(type: "clients")]
final class Client
{

    #[ResourceIdentifier]
    private readonly ClientId $clientId;

    #[Attribute]
    private readonly string $name;

    #[Attribute]
    private readonly string $secret;

    private readonly string $public;

    #[Attribute(name: "isConfidential")]
    private readonly bool $confidential;

    #[Attribute]
    private readonly ?array $redirectUri;

    /**
     * Creates a Client
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->clientId = new ClientId($data['id']);
        $this->name = $data['name'];
        $this->secret = $data['secret'];
        $this->public = $data['confidential'] ? 'false' : 'true';
        $this->confidential = (bool) $data['confidential'];
        $this->redirectUri = $data['redirect_uri'] ? explode(',', $data['redirect_uri']) : null;
    }

    /**
     * clientId
     *
     * @return ClientId
     */
    public function clientId(): ClientId
    {
        return $this->clientId;
    }

    /**
     * name
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * secret
     *
     * @return string
     */
    public function secret(): string
    {
        return $this->secret;
    }

    /**
     * public
     *
     * @return string
     */
    public function isPublic(): string
    {
        return $this->public;
    }

    /**
     * confidential
     *
     * @return bool
     */
    public function isConfidential(): bool
    {
        return $this->confidential;
    }

    /**
     * redirectUri
     *
     * @return array|null
     */
    public function redirectUri(): ?array
    {
        return $this->redirectUri;
    }
}