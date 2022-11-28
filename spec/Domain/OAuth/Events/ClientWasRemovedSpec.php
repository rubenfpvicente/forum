<?php

namespace spec\App\Domain\OAuth\Events;

use App\Domain\AbstractEvent;
use App\Domain\OAuth\Client\ClientId;
use App\Domain\OAuth\Events\ClientWasRemoved;
use DateTimeImmutable;
use PhpSpec\ObjectBehavior;
use Symfony\Contracts\EventDispatcher\Event;

class ClientWasRemovedSpec extends ObjectBehavior
{

    private $clientId;

    function let()
    {
        $this->clientId = new ClientId('some-id');
        $this->beConstructedWith($this->clientId);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ClientWasRemoved::class);
    }

    function its_an_event()
    {
        $this->shouldBeAnInstanceOf(Event::class);
        $this->shouldHaveType(AbstractEvent::class);
        $this->occurredOn()->shouldBeAnInstanceOf(DateTimeImmutable::class);
    }


    function it_has_a_clientId()
    {
        $this->clientId()->shouldBe($this->clientId);
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'clientId' => $this->clientId
        ]);
    }
}
