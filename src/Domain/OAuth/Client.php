<?php

namespace App\Domain\OAuth;

use App\Domain\OAuth\Client\ClientId;
use App\Domain\OAuth\Events\ClientDetailsHasChanged;
use App\Domain\OAuth\Events\ClientWasAdded;
use App\Domain\OAuth\Events\RedirectUriWasAdded;
use App\Domain\OAuth\Events\RedirectUriWasRemoved;
use App\Domain\RootAggregate;
use App\Domain\RootAggregateMethods;
use App\Domain\UserManagement\User\Password;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use League\OAuth2\Server\Entities\ClientEntityInterface;

/**
 * Client
 *
 * @package App\Domain\OAuth
 * @Entity()
 */
#[Entity]
#[Table(name: "clients")]
class Client implements RootAggregate, ClientEntityInterface
{
    use RootAggregateMethods;

    #[Column(options: ['default' => 1])]
    private bool $confidential = true;

    /**
     * Creates a Client
     *
     * @param ClientId $clientId
     * @param string $name
     * @param string|null $secret
     * @param array|null $redirectUri
     */
    public function __construct(
        #[Id]
        #[GeneratedValue(strategy: 'NONE')]
        #[Column(name: 'id', type: 'ClientId')]
        private ClientId $clientId,

        #[Column]
        private string $name,

        #[Column(nullable: true)]
        private ?string $secret = null,

        #[Column(type: "simple_array", nullable: true)]
        private ?array $redirectUri = []
    ) {
        $this->secret = $this->secret ?: Password::randomPassword(16);
        $this->recordThat(new ClientWasAdded(
            clientId: $this->clientId,
            name: $this->name,
            secret: $this->secret,
            redirectUri: $this->redirectUri
        ));
    }

    /**
     * Creates a public client
     *
     * @param ClientId $clientId
     * @param string $name
     * @param array $redirectUri
     * @param string|null $secret
     * @return Client
     */
    public static function publicClient(
        ClientId $clientId,
        string $name,
        array $redirectUri,
        ?string $secret = null
    ): Client {
        $client = new Client(
            clientId: $clientId, name: $name, secret: $secret, redirectUri: $redirectUri
        );
        $client->releaseEvents();
        $client->confidential = false;
        $client->recordThat(new ClientWasAdded(
            $client->clientId(),
            $client->name(),
            $client->secret(),
            $client->isConfidential(),
            $client->redirectUri()
        ));
        return $client;
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
     * Adds a redirect URI to the redirect URI list
     *
     * @param string $newUri
     * @return Client
     */
    public function addRedirectUri(string $newUri): static
    {
        foreach ($this->redirectUri as $uri) {
            if ($uri === $newUri) {
                return $this;
            }
        }
        $this->redirectUri[] = $newUri;
        $this->recordThat(new RedirectUriWasAdded($this->clientId, $newUri));
        return $this;
    }

    /**
     * Removes a redirect URI entry
     *
     * @param string $existingUri
     * @return Client
     */
    public function removeRedirectUri(string $existingUri): static
    {
        $uris = [];
        foreach ($this->redirectUri as $uri) {
            if ($uri === $existingUri) {
                $this->recordThat(new RedirectUriWasRemoved($this->clientId, $existingUri));
                continue;
            }
            $uris[] = $uri;
        }
        $this->redirectUri = $uris;
        return $this;
    }

    /**
     * Change client details
     *
     * @param ClientId|null $newClientId
     * @param string|null $name
     * @param string|null $secret
     * @return Client
     */
    public function changeDetails(
        ?ClientId $newClientId = null,
        ?string $name = null,
        ?string $secret = null
    ): static {
        $oldClientId = $this->clientId;

        $this->clientId = $newClientId ?: $this->clientId;
        $this->name = $name ?: $this->name;
        $this->secret = $secret ?: $this->secret;

        $this->recordThat(new ClientDetailsHasChanged(
            $oldClientId,
            $this->clientId,
            $this->name,
            $this->secret
        ));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier(): string
    {
        return (string) $this->clientId();
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name();
    }

    /**
     * @inheritDoc
     */
    public function getRedirectUri(): array|string
    {
        return $this->redirectUri();
    }
}
