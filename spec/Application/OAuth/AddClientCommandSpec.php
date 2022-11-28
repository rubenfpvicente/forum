<?php

namespace spec\App\Application\OAuth;

use App\Application\Command;
use App\Application\OAuth\AddClientCommand;
use App\Domain\OAuth\Client\ClientId;
use App\Domain\UserManagement\User\Password;
use PhpSpec\ObjectBehavior;

class AddClientCommandSpec extends ObjectBehavior
{

    private $clientId;
    private $name;
    private $secret;
    private $isConfidential;
    private $redirectUri;

    function let()
    {
        $this->clientId = new ClientId('some-id');
        $this->name = 'App name';
        $this->secret = Password::randomPassword(16);
        $this->isConfidential = true;
        $this->redirectUri = ['http://example.com'];
        $this->beConstructedWith(
            $this->clientId,
            $this->name,
            $this->secret,
            $this->isConfidential,
            $this->redirectUri
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AddClientCommand::class);
    }

    function its_a_command()
    {
        $this->shouldBeAnInstanceOf(Command::class);
    }

    function it_has_a_client_id()
    {
        $this->clientId()->shouldBe($this->clientId);
    }

    function it_has_a_name()
    {
        $this->name()->shouldBe($this->name);
    }

    function it_has_a_secret()
    {
        $this->secret()->shouldBe($this->secret);
    }

    function it_has_a_confidential_state()
    {
        $this->isConfidential()->shouldBe($this->isConfidential);
    }

    function it_has_a_redirect_uri()
    {
        $this->redirectUri()->shouldBe($this->redirectUri);
    }
}
