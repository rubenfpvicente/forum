<?php

namespace App\Application\UserManagement;

use App\Application\Command;
use App\Application\CommandHandler;
use App\Domain\UserManagement\User;
use App\Domain\UserManagement\UserRepository;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * RegisterUserHandler
 *
 * @package App\Application\UserManagement
 */
class RegisterUserHandler implements CommandHandler
{

    /**
     * Creates a RegisterUserHandler
     *
     * @param UserRepository $users
     * @param EventDispatcherInterface $
     */
    public function __construct(
        private UserRepository $users,
        private EventDispatcherInterface $dispatcher
    ) {
    }

    /**
     * @inheritDoc
     */
    public function handle(Command|RegisterUserCommand $command): User
    {
        $user = new User(
            $command->name(),
            $command->email(),
            $command->password()
        );

        $user = $this->users->add($user);
        foreach ($user->releaseEvents() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $user;
    }
}
