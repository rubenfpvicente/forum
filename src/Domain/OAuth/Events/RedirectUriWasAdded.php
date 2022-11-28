<?php

namespace App\Domain\OAuth\Events;

use App\Domain\AbstractEvent;
use App\Domain\OAuth\Client\ClientId;
use JsonSerializable;

/**
 * RedirectUriWasAdded
 *
 * @package App\Domain\OAuth\Events
 */
class RedirectUriWasAdded extends AbstractEvent implements JsonSerializable
{
    /**
     * Creates a RedirectUriWasAdded
     *
     * @param ClientId $clientId
     * @param string $uri
     */
    public function __construct(
        protected readonly ClientId $clientId,
        protected readonly string $uri
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
     * uri
     *
     * @return string
     */
    public function uri(): string
    {
        return $this->uri;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return [
            'clientId' => $this->clientId,
            'uri' => $this->uri
        ];
    }
}
