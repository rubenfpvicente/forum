<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\Command\OAuth;

use App\Application\OAuth\AddClientCommand;
use App\Application\OAuth\AddClientHandler;
use App\Domain\OAuth\Client;
use App\Domain\OAuth\Client\ClientId;
use App\Domain\UserManagement\User\Password;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * AddClient
 *
 * @package App\Infrastructure\Command\OAuth
 */
#[AsCommand(
    name: "app:client:add",
    description: "Adds a new OAuth2 client application",
    aliases: ["clsadd"]
)]
final class AddClient extends Command
{
    private ?SymfonyStyle $style = null;
    private ?ClientId $clientId = null;
    private ?string $name = null;
    private ?string $secret = null;
    private ?array $redirectUri = null;


    public function __construct(
        private readonly AddClientHandler       $handler,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $secret = $this->secret ?: Password::randomPassword(24);
            $client = $this->handler->handle(
                new AddClientCommand($this->clientId, $this->name, $secret, !$input->getOption('public'), $this->redirectUri)
            );
            $this->entityManager->flush();
            $this->renderOutput($client, $input);
        } catch (\Throwable $e) {
            $this->style->error($e->getMessage());
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->addArgument(name: "clientId", mode: InputArgument::REQUIRED, description: "Client identifier.")
            ->addArgument(name: "name", mode: InputArgument::REQUIRED, description: "Client name.")
            ->addArgument(name: "redirectUri", mode: InputArgument::IS_ARRAY, description: "List of redirect URI")

            ->addOption(name: "secret", shortcut: "s", mode: InputOption::VALUE_OPTIONAL, description: "Secret used in authentication processes.")
            ->addOption(name: "public", shortcut: "p", mode: InputOption::VALUE_NEGATABLE, description: "Whenever the client is a public application")
        ;
    }

    /**
     * @inheritDoc
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $this->checkClientId($input);
        $this->checkName($input);
    }

    /**
     * @inheritDoc
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->style = new SymfonyStyle($input, $output);
        $clientId = $input->getArgument('clientId');
        $this->clientId = $clientId ? new ClientId($clientId) : null;
        $this->name = $input->getArgument('name');
        $this->secret = $input->getOption('secret');
        $this->redirectUri = $input->getArgument('redirectUri');
    }

    private function checkClientId(InputInterface $input): void
    {
        if ($this->clientId) {
            return;
        }

        $this->clientId = $this->style->ask(
            question: 'Client ID',
            validator: function ($response) use ($input) {
                if (!is_string($response) || strlen($response) <= 0) {
                    throw new \RuntimeException(
                        "Client ID cannot be null."
                    );
                }
                $input->setArgument('clientId', $response);
                return new ClientId($response);
            }
        );
    }

    private function checkName(InputInterface $input): void
    {
        if ($this->name) {
            return;
        }

        $this->name = $this->style->ask(
            question: 'Client name',
            validator: function ($response) use ($input) {
                if (!is_string($response) || strlen($response) <= 0) {
                    throw new \RuntimeException(
                        "Client name cannot be null."
                    );
                }
                $input->setArgument('name', $response);
                return $response;
            }
        );
    }

    private function renderOutput(Client $client)
    {
        $this->style->success("Client \"{$client->name()}\" successfully created.");
        $this->style->info("For authentication use the following:\nClient ID:\t{$client->clientId()}\nClient Secret:\t{$client->secret()}");
    }
}
