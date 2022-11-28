<?php

namespace App\Domain\OAuth\Events;

use App\Domain\AbstractEvent;
use App\Domain\OAuth\Client\ClientId;
use JsonSerializable;

class ClientDetailsHasChanged extends AbstractEvent implements JsonSerializable
{
    /**
     * Creates a ClientDetailsHasChanged
     *
     * @param ClientId $oldClientId
     * @param ClientId $newClientId
     * @param string $name
     * @param string $secret
     */
    public function __construct(
        private readonly ClientId $oldClientId,
        private readonly ClientId $newClientId,
        private readonly string $name,
        private readonly string $secret
    ) {
        parent::__construct();
    }

    /**
     * oldClientId
     *
     * @return ClientId
     */
    public function oldClientId(): ClientId
    {
        return $this->oldClientId;
    }

    /**
     * newClientId
     *
     * @return ClientId
     */
    public function newClientId(): ClientId
    {
        return $this->newClientId;
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
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
        'oldClientId' => $this->oldClientId,
            'newClientId' => $this->newClientId,
            'name' => $this->name,
            'secret' => $this->secret
        ];
    }
}
