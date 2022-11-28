<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Domain\OAuth;

use App\Domain\Exception\EntityNotFound;
use App\Domain\OAuth\Client\ClientId;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use RuntimeException;

/**
 * ClientRepository
 *
 * @package App\Domain\OAuth
 */
interface ClientRepository extends ClientRepositoryInterface
{

    /**
     * Adds a client to the repository
     *
     * @param Client $client
     * @return Client
     */
    public function add(Client $client): Client;

    /**
     * Retrieves the client that was added with the specified identifier
     *
     * @param ClientId $clientId
     * @return Client
     *
     * @throws RuntimeException|EntityNotFound
     */
    public function withId(ClientId $clientId): Client;

}
