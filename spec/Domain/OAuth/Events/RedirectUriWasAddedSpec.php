<?php

namespace spec\App\Domain\OAuth\Events;

use App\Domain\AbstractEvent;
use App\Domain\OAuth\Client\ClientId;
use App\Domain\OAuth\Events\RedirectUriWasAdded;
use DateTimeImmutable;
use JsonSerializable;
use PhpSpec\ObjectBehavior;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * RedirectUriWasAddedSpec
 *
 * @package spec\App\Domain\OAuth\Events
 */
class RedirectUriWasAddedSpec extends ObjectBehavior
{

    private $clientId;
    private $uri;

    function let()
    {
        $this->clientId = new ClientId('some-id');
        $this->uri = 'https://www.example.com';
        $this->beConstructedWith($this->clientId, $this->uri);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RedirectUriWasAdded::class);
    }

    function its_an_event()
    {
        $this->shouldBeAnInstanceOf(Event::class);
        $this->shouldHaveType(AbstractEvent::class);
        $this->occurredOn()->shouldBeAnInstanceOf(DateTimeImmutable::class);
    }


    function it_has_a_client_id()
    {
        $this->clientId()->shouldBe($this->clientId);
    }

    function it_has_a_uri()
    {
        $this->uri()->shouldBe($this->uri);
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'clientId' => $this->clientId,
            'uri' => $this->uri
        ]);
    }
}
