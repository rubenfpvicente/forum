<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\Command\OAuth;

use App\Application\OAuth\ClientListQuery;
use App\Application\OAuth\Model\Client;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * ListClients
 *
 * @package App\Infrastructure\Command\OAuth
 */
#[AsCommand(
    name: "app:client:list",
    description: "Lists all available OAuth2.0 clients",
    aliases: ["app:clients", "cls"]
)]
final class ListClients extends Command
{

    private ?SymfonyStyle $style = null;

    public function __construct(
        private ClientListQuery $clientListQuery
    ) {
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->style = new SymfonyStyle($input, $output);
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rows = [];
        /** @var Client $client */
        foreach ($this->clientListQuery as $client) {
            $uris = $client->redirectUri();
            $rows[] = [$client->clientId(), $client->name(), $client->isPublic(), $client->secret(), $uris ? implode(',', $uris): null];
        }
        $this->style->table(["Clint ID", "Name", "Public?", "Secret", "Redirect URIs"], $rows);

        return Command::SUCCESS;
    }
}