<?php

namespace spec\App\Application\OAuth;

use App\Application\CommandHandler;
use App\Application\OAuth\AddClientCommand;
use App\Application\OAuth\AddClientHandler;
use App\Domain\OAuth\Client;
use App\Domain\OAuth\ClientRepository;
use App\Domain\OAuth\Events\ClientWasAdded;
use App\Domain\UserManagement\User\Password;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\EventDispatcher\EventDispatcherInterface;

class AddClientHandlerSpec extends ObjectBehavior
{

    function let(
        ClientRepository $clients,
        EventDispatcherInterface $dispatcher
    ) {
        $clients->add(Argument::type(Client::class))->willReturnArgument();
        $dispatcher->dispatch(Argument::type(ClientWasAdded::class))->willReturnArgument();

        $this->beConstructedWith($clients, $dispatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AddClientHandler::class);
    }

    function its_a_command_handler()
    {
        $this->shouldBeAnInstanceOf(CommandHandler::class);
    }

    function it_handles_add_client_command(
        ClientRepository $clients,
        EventDispatcherInterface $dispatcher
    ) {
        $command = new AddClientCommand(
            new Client\ClientId('some-id'),
            'My App',
            Password::randomPassword(16)
        );

        $client = $this->handle($command);
        $client->shouldBeAnInstanceOf(Client::class);

        $clients->add($client)->shouldHaveBeenCalled();
        $dispatcher->dispatch(Argument::type(ClientWasAdded::class))->shouldHaveBeenCalled();
    }

    function it_can_handle_public_client_creation()
    {
        $command = new AddClientCommand(
            new Client\ClientId('some-id'),
            'My App',
            Password::randomPassword(16),
            false,
            ['http://example.com']
        );

        $client = $this->handle($command);
        $client->isConfidential()->shouldBe(false);
    }

}
