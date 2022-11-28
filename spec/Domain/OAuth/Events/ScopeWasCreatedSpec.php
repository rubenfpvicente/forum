<?php

namespace spec\App\Domain\OAuth\Events;

use App\Domain\AbstractEvent;
use App\Domain\OAuth\Events\ScopeWasCreated;
use App\Domain\OAuth\Scope\ScopeId;
use DateTimeImmutable;
use PhpSpec\ObjectBehavior;
use Symfony\Contracts\EventDispatcher\Event;

class ScopeWasCreatedSpec extends ObjectBehavior
{

    private $description;
    private $scopeId;

    function let()
    {
        $this->description = 'a description';
        $this->scopeId = new ScopeId('some-id');
        $this->beConstructedWith($this->scopeId, $this->description);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ScopeWasCreated::class);
    }

    function its_an_event()
    {
        $this->shouldBeAnInstanceOf(Event::class);
        $this->shouldHaveType(AbstractEvent::class);
        $this->occurredOn()->shouldBeAnInstanceOf(DateTimeImmutable::class);
    }

    function it_has_a_scopeId()
    {
        $this->scopeId()->shouldBe($this->scopeId);
    }

    function it_has_a_description()
    {
        $this->description()->shouldBe($this->description);
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'scopeId' => $this->scopeId,
            'description' => $this->description
        ]);
    }

}
