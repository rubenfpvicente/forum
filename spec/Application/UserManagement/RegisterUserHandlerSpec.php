<?php

namespace spec\App\Application\UserManagement;

use App\Application\CommandHandler;
use App\Application\UserManagement\RegisterUserCommand;
use App\Application\UserManagement\RegisterUserHandler;
use App\Domain\UserManagement\Events\UserWasRegistered;
use App\Domain\UserManagement\User;
use App\Domain\UserManagement\UserRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\EventDispatcher\EventDispatcherInterface;

class RegisterUserHandlerSpec extends ObjectBehavior
{

    function let(
        UserRepository $users,
        EventDispatcherInterface $dispatcher
    ) {
        $users->add(Argument::type(User::class))->willReturnArgument();
        $dispatcher->dispatch(Argument::type(UserWasRegistered::class))->willReturnArgument();

        $this->beConstructedWith($users, $dispatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RegisterUserHandler::class);
    }

    function its_a_command_handler()
    {
        $this->shouldBeAnInstanceOf(CommandHandler::class);
    }

    function it_handles_register_user_command(
        UserRepository $users,
        EventDispatcherInterface $dispatcher
    ) {
        $password = 'secret-pass';
        $email = new User\Email('john.doe@example.com');
        $name = 'John Doe';
        $command = new RegisterUserCommand($name, $email, new User\Password($password));
        $user = $this->handle($command);
        $user->shouldBeAnInstanceOf(User::class);
        $user->name()->shouldBe($name);
        $user->password()->match($password)->shouldBe(true);
        $user->email()->shouldBe($email);

        $users->add($user)->shouldHaveBeenCalled();
        $dispatcher->dispatch(Argument::type(UserWasRegistered::class))->shouldHaveBeenCalled();
    }
}
