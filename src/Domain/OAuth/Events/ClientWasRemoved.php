<?php

namespace App\Domain\OAuth\Events;

use App\Domain\AbstractEvent;
use App\Domain\OAuth\Client\ClientId;
use JsonSerializable;

class ClientWasRemoved extends AbstractEvent implements JsonSerializable
{

    /**
     * Creates a ClientWasRemoved
     *
     * @param ClientId $clientId
     */
    public function __construct(private readonly ClientId $clientId)
    {
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
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return ['clientId' => $this->clientId];
    }
}
