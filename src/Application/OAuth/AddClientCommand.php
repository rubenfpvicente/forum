<?php

namespace App\Application\OAuth;

use App\Application\Command;
use App\Domain\OAuth\Client\ClientId;

/**
 * AddClientCommand
 *
 * @package App\Application\OAuth
 */
class AddClientCommand implements Command
{
    /**
     * Creates a AddClientCommand
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
}
