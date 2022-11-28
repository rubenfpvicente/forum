<?php

namespace App\Application\OAuth;

use App\Application\Command;
use App\Application\CommandHandler;
use App\Application\CommandHandlerMethods;
use App\Domain\OAuth\Client;
use App\Domain\OAuth\ClientRepository;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * AddClientHandler
 *
 * @package App\Application\OAuth
 */
class AddClientHandler implements CommandHandler
{
    use CommandHandlerMethods;

    /**
     * Creates a AddClientHandler
     *
     * @param ClientRepository $clientRepository
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        private ClientRepository $clientRepository,
        private EventDispatcherInterface $dispatcher
    ) {
    }

    /**
     * @inheritDoc
     */
    public function handle(Command|AddClientCommand $command): Client
    {
        $client = $this->createClient($command);

        $this->dispatchEventsFrom(
            $this->clientRepository->add($client),
            $this->dispatcher
        );

        return $client;
    }

    /**
     * getClient
     *
     * @param AddClientCommand|Command $command
     * @return Client
     */
    private function createClient(AddClientCommand|Command $command): Client
    {
        if ($command->isConfidential()) {
            return new Client(
                clientId: $command->clientId(),
                name: $command->name(),
                secret: $command->secret(),
                redirectUri: $command->redirectUri()
            );
        }

        return Client::publicClient(
            clientId: $command->clientId(),
            name: $command->name(),
            redirectUri: $command->redirectUri(),
            secret: $command->secret()
        );
    }
}
