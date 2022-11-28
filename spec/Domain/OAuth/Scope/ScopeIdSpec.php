<?php

namespace spec\App\Domain\OAuth\Scope;

use App\Domain\Comparable;
use App\Domain\OAuth\Scope\ScopeId;
use PhpSpec\ObjectBehavior;

class ScopeIdSpec extends ObjectBehavior
{

    private $identifier;

    function let()
    {
        $this->identifier = 'forum';
        $this->beConstructedWith($this->identifier);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ScopeId::class);
    }

    function it_can_be_converted_to_string()
    {
        $this->shouldBeAnInstanceOf(\Stringable::class);
        $this->__toString()->shouldBe($this->identifier);
    }

    function it_can_be_compared_()
    {
        $this->shouldBeAnInstanceOf(Comparable::class);
        $this->equalsTo(new ScopeId($this->identifier))->shouldBe(true);
        $this->equalsTo((object)['identifier' => $this->identifier])->shouldBe(false);
        $this->equalsTo(new ScopeId('other-id'))->shouldBe(false);
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe($this->identifier);
    }
}
