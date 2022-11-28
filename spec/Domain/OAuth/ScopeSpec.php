<?php

namespace spec\App\Domain\OAuth;

use App\Domain\OAuth\Events\ScopeWasCreated;
use App\Domain\OAuth\Scope;
use App\Domain\RootAggregate;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use PhpSpec\ObjectBehavior;

class ScopeSpec extends ObjectBehavior
{

    private $scopeId;
    private $description;

    function let()
    {
        $this->scopeId = new Scope\ScopeId('some-aid');
        $this->description = 'A description';
        $this->beConstructedWith($this->scopeId, $this->description);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Scope::class);
    }

    function its_a_root_aggregate()
    {
        $this->shouldBeAnInstanceOf(RootAggregate::class);
        $events = $this->releaseEvents();
        $events->shouldHaveCount(1);
        $events[0]->shouldBeAnInstanceOf(ScopeWasCreated::class);
    }

    function it_has_a_scopeId()
    {
        $this->scopeId()->shouldBe($this->scopeId);
    }

    function it_has_a_description()
    {
        $this->description()->shouldBe($this->description);
    }

    function its_an_oauth2_scope()
    {
        $this->shouldBeAnInstanceOf(ScopeEntityInterface::class);
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe((string) $this->scopeId);
    }
}
