<?php

namespace spec\App\Domain\OAuth\Events;

use App\Domain\AbstractEvent;
use App\Domain\OAuth\Client\ClientId;
use App\Domain\OAuth\Events\ClientDetailsHasChanged;
use DateTimeImmutable;
use PhpSpec\ObjectBehavior;
use Symfony\Contracts\EventDispatcher\Event;

class ClientDetailsHasChangedSpec extends ObjectBehavior
{

    private $oldClientId;
    private $newClientId;
    private $name;
    private $secret;

    function let()
    {
        $this->oldClientId = new ClientId('old-id');
        $this->newClientId = new ClientId('new-id');
        $this->name = 'some name';
        $this->secret = 'some-secret';
        $this->beConstructedWith($this->oldClientId, $this->newClientId, $this->name, $this->secret);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ClientDetailsHasChanged::class);
    }

    function its_an_event()
    {
        $this->shouldBeAnInstanceOf(Event::class);
        $this->shouldHaveType(AbstractEvent::class);
        $this->occurredOn()->shouldBeAnInstanceOf(DateTimeImmutable::class);
    }


    function it_has_a_oldClientId()
    {
        $this->oldClientId()->shouldBe($this->oldClientId);
    }

    function it_has_a_newClientId()
    {
        $this->newClientId()->shouldBe($this->newClientId);
    }

    function it_has_a_name()
    {
        $this->name()->shouldBe($this->name);
    }

    function it_has_a_secret()
    {
        $this->secret()->shouldBe($this->secret);
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'oldClientId' => $this->oldClientId,
            'newClientId' => $this->newClientId,
            'name' => $this->name,
            'secret' => $this->secret
        ]);
    }
}
