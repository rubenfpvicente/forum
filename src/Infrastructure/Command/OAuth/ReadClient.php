<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);


namespace App\Infrastructure\Command\OAuth;

use App\Domain\OAuth\Client\ClientId;
use App\Domain\OAuth\ClientRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * ReadClient
 *
 * @package App\Infrastructure\Command\OAuth
 */
#[AsCommand(
    name: "app:client:read",
    description: "Outputs a specified client",
    aliases: ["cl", "app:client"]
)]
final class ReadClient extends Command
{

    private SymfonyStyle $style;

    public function __construct(
        private ClientRepository $clients
    ) {
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->addArgument(name: 'clientId', mode: InputArgument::REQUIRED, description: "The client identifier");
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
        $client = $this->clients->withId(new ClientId($input->getArgument('clientId')));
        $isConfidential = $client->isConfidential() ? 'Yes' : 'No';
        $rows[] = [$client->clientId(), $client->name(), $isConfidential, $client->secret(), implode(', ', $client->redirectUri())];
        $this->style->table(["Clint ID", "Name", "Confidential?", "Secret", "Redirect URIs"], $rows);
        return Command::SUCCESS;
    }
}