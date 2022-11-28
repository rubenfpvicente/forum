<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\Command\UserManagement;

use App\Application\UserManagement\RegisterUserCommand;
use App\Application\UserManagement\RegisterUserHandler;
use App\Domain\UserManagement\User\Email;
use App\Domain\UserManagement\User\Password;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * RegisterUser
 *
 * @package App\Infrastructure\Command\UserManagement
 */
#[AsCommand(
    name: "app:user:register",
    description: "Registers a new user on the database.",
    aliases: ["useradd", "usradd"]
)]
final class RegisterUser extends Command
{
    private SymfonyStyle $style;
    private string $name;
    private Email $email;
    private Password $password;

    public function __construct(private RegisterUserHandler $handler, private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setHelp("Adds a new user to the database.")
            ->addArgument(name: 'name', description: "User full name")
            ->addArgument(name: 'email', description: "User's email address")
            ->addArgument(name: 'password', description: "Password for authentication processes")
        ;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): mixed
    {
        try {
            $user = $this->handler->handle(
                new RegisterUserCommand($this->name, $this->email, $this->password)
            );
            $this->entityManager->flush();
            $this->style->success("User {$user->name()} successfully registered.");

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->style->error($e->getMessage());
        }
        return Command::FAILURE;
    }

    /**
     * @inheritDoc
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $this->checkName($input);
        $this->checkEmail($input);
        $this->checkPassword($input);
    }

    /**
     * @inheritDoc
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->style = new SymfonyStyle($input, $output);
    }

    private function checkName(InputInterface $input)
    {
        $argumentName = $input->getArgument('name');
        if ($argumentName) {
            $this->name = $argumentName;
            return;
        }

        $this->name = $this->style->ask(
            question: "User name",
            validator: function ($answer) {
                if (!is_string($answer) || strlen($answer) <= 0) {
                    throw new \RuntimeException(
                        "User name cannot be null."
                    );
                }

                return $answer;
            }
        );
    }

    private function checkEmail(InputInterface $input)
    {
        $argumentEmail = $input->getArgument('email');
        if ($argumentEmail) {
            $this->email = new Email($argumentEmail);
            return;
        }

        $this->email = $this->style->ask(
            question: "User email address",
            validator: function ($answer) {
                if (!is_string($answer) || strlen($answer) <= 0) {
                    throw new \RuntimeException(
                        "User email cannot be null."
                    );
                }

                return new Email($answer);
            }
        );
    }

    private function checkPassword(InputInterface $input)
    {
        $argumentPassword = $input->getArgument('password');
        if ($argumentPassword) {
            $this->password = new Password($argumentPassword);
            return;
        }

        $this->password = $this->style->askHidden(
            question: "Password",
            validator: function ($answer) {
                if (!is_string($answer) || strlen($answer) < 8) {
                    throw new \RuntimeException(
                        "Password must have at least 8 characters long"
                    );
                }

                return new Password($answer);
            }
        );
    }
}