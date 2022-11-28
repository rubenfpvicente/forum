<?php

namespace spec\App\Domain\OAuth\Client;

use App\Domain\Comparable;
use App\Domain\OAuth\Client\ClientId;
use PhpSpec\ObjectBehavior;

class ClientIdSpec extends ObjectBehavior
{

    private $identifier;

    function let()
    {
        $this->identifier = 'some-crazy-id';
        $this->beConstructedWith($this->identifier);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ClientId::class);
    }

    function it_can_be_converted_to_string()
    {
        $this->shouldBeAnInstanceOf(\Stringable::class);
        $this->__toString()->shouldBe($this->identifier);
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe($this->identifier);
    }

    function it_can_be_compared()
    {
        $this->shouldBeAnInstanceOf(Comparable::class);
        $this->equalsTo(new ClientId($this->identifier))->shouldBe(true);
        $this->equalsTo($this->identifier)->shouldBe(false);
        $this->equalsTo(new ClientId('other-id'))->shouldBe(false);
    }
}
