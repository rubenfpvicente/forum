<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\Command\UserManagement;

use App\Application\UserManagement\UserListQuery;
use App\Domain\UserManagement\User;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * UserList
 *
 * @package App\Infrastructure\Command\UserManagement
 */
#[AsCommand(
    name: "app:user:list",
    description: "Lists all available application users.",
    aliases: ["app:users", "users"]
)]
final class UserList extends Command
{

    private ?SymfonyStyle $style = null;

    public function __construct(private readonly UserListQuery $userList)
    {
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
        foreach ($this->userList as $user) {
            $rows[] = [$user['name'], $user['email']];
        }
        $this->style->table(["Name", "Email"], $rows);

        return Command::SUCCESS;
    }
}
