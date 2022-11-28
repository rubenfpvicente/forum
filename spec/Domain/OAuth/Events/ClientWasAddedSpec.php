<?php

namespace spec\App\Domain\OAuth\Events;

use App\Domain\AbstractEvent;
use App\Domain\DomainEvent;
use App\Domain\OAuth\Client\ClientId;
use App\Domain\OAuth\Events\ClientWasAdded;
use DateTimeImmutable;
use PhpSpec\ObjectBehavior;

class ClientWasAddedSpec extends ObjectBehavior
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
        $this->secret = 'some-random-and-crazy-secret';
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
        $this->shouldHaveType(ClientWasAdded::class);
    }

    function its_an_event()
    {
        $this->shouldBeAnInstanceOf(DomainEvent::class);
        $this->shouldHaveType(AbstractEvent::class);
        $this->occurredOn()->shouldBeAnInstanceOf(DateTimeImmutable::class);
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

    function it_has_a_confidential_status()
    {
        $this->isConfidential()->shouldBe($this->isConfidential);
    }

    function it_has_a_redirect_uri()
    {
        $this->redirectUri()->shouldBe($this->redirectUri);
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'clientId' => $this->clientId,
            'name' => $this->name,
            'secret' => $this->secret,
            'confidential' => $this->isConfidential,
            'redirectUri' => $this->redirectUri
        ]);
    }
}
