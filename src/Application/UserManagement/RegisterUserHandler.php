<?php

namespace App\Application\UserManagement;

use App\Application\Command;
use App\Application\CommandHandler;
use App\Application\CommandHandlerMethods;
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

    use CommandHandlerMethods;

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
        $this->dispatchEventsFrom($user, $this->dispatcher);

        return $user;
    }
}
