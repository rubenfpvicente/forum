<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\OAuth;

use App\Domain\Exception\EntityNotFound;
use App\Domain\OAuth\Client;
use App\Domain\OAuth\Client\ClientId;
use App\Domain\OAuth\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;

/**
 * DoctrineClientRepository
 *
 * @package App\Infrastructure\Persistence\Doctrine\OAuth
 */
final class DoctrineClientRepository implements ClientRepository
{

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @inheritDoc
     */
    public function add(Client $client): Client
    {
        $this->entityManager->persist($client);
        return $client;
    }

    /**
     * @inheritDoc
     */
    public function withId(ClientId $clientId): Client
    {
        $client = $this->entityManager->find(Client::class, $clientId);
        if ($client instanceof Client) {
            return $client;
        }

        throw new EntityNotFound(
            "No client found with ID '$clientId'."
        );
    }

    /**
     * @inheritDoc
     */
    public function getClientEntity($clientIdentifier): ClientEntityInterface|Client|null
    {
        try {
            $client = $this->withId(new ClientId($clientIdentifier));
        } catch (EntityNotFound) {
            return null;
        }

        return $client;
    }

    /**
     * @inheritDoc
     */
    public function validateClient($clientIdentifier, $clientSecret, $grantType): bool
    {
        try {
            $client = $this->withId(new ClientId($clientIdentifier));
        } catch (EntityNotFound) {
            return false;
        }

        return $clientSecret === $client->secret();
    }
}
