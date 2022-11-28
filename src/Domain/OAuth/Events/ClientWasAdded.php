<?php

namespace App\Domain\OAuth\Events;

use App\Domain\AbstractEvent;
use App\Domain\DomainEvent;
use App\Domain\OAuth\Client\ClientId;
use JsonSerializable;

/**
 * ClientWasAdded
 *
 * @package App\Domain\OAuth\Events
 */
class ClientWasAdded extends AbstractEvent implements DomainEvent, JsonSerializable
{
    /**
     * Creates a ClientWasAdded
     *
     * @param ClientId $clientId
     * @param string $name
     * @param string $secret
     * @param bool $confidential
     * @param array $redirectUri
     */
    public function __construct(
        private readonly ClientId $clientId,
        private readonly string   $name,
        private readonly string   $secret,
        private readonly bool     $confidential = true,
        private readonly array    $redirectUri = []
    ) {
        parent::__construct();
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
     * isConfidential
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
     * @return array
     */
    public function redirectUri(): array
    {
        return $this->redirectUri;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return [
            'clientId' => $this->clientId,
            'name' => $this->name,
            'secret' => $this->secret,
            'confidential' => $this->confidential,
            'redirectUri' => $this->redirectUri
        ];
    }
}
