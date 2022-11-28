<?php

namespace spec\App\Domain\OAuth;

use App\Domain\OAuth\Client;
use App\Domain\OAuth\Client\ClientId;
use App\Domain\OAuth\Events\ClientDetailsHasChanged;
use App\Domain\OAuth\Events\ClientWasAdded;
use App\Domain\OAuth\Events\RedirectUriWasAdded;
use App\Domain\OAuth\Events\RedirectUriWasRemoved;
use App\Domain\RootAggregate;
use App\Domain\UserManagement\User\Password;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use PhpSpec\ObjectBehavior;

class ClientSpec extends ObjectBehavior
{

    private $clientId;
    private $name;
    private $secret;

    function let()
    {
        $this->clientId = new Client\ClientId('my-app');
        $this->name = 'My app';
        $this->secret = Password::randomPassword(16);
        $this->beConstructedWith($this->clientId, $this->name, $this->secret);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Client::class);
    }

    function ist_am_oauth2_client()
    {
        $this->shouldBeAnInstanceOf(ClientEntityInterface::class);
    }

    function it_has_a_clientId()
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

    function it_can_be_created_without_a_secret()
    {
        $this->beConstructedWith($this->clientId, $this->name);
        $this->secret()->shouldMatch('/.{16}/i');
    }

    function it_has_a_is_confidential()
    {
        $this->isConfidential()->shouldBe(true);
    }

    function it_has_a_redirect_uri()
    {
        $this->redirectUri()->shouldBe([]);
    }

    function it_can_be_constructed_as_a_public_client()
    {
        $redirectUri = ['https://example.com'];
        $this->beConstructedThrough('publicClient', [$this->clientId, $this->name, $redirectUri]);
        $this->redirectUri()->shouldBe($redirectUri);
        $this->isConfidential()->shouldBe(false);
        $events = $this->releaseEvents();
        $events->shouldHaveCount(1);
        $events[0]->shouldBeAnInstanceOf(ClientWasAdded::class);
        $events[0]->isConfidential()->shouldBe(false);
    }

    function its_a_root_aggregate()
    {
        $this->shouldBeAnInstanceOf(RootAggregate::class);
        $events = $this->releaseEvents();
        $events->shouldHaveCount(1);
        $events[0]->shouldBeAnInstanceOf(ClientWasAdded::class);
        $events[0]->isConfidential()->shouldBe(true);
    }

    function it_can_add_redirect_uri()
    {
        $uri = 'http://example.org';
        $this->releaseEvents();
        $this->addRedirectUri($uri)->shouldBe($this->getWrappedObject());
        $this->redirectUri()->shouldBe([$uri]);
        $events = $this->releaseEvents();
        $events->shouldHaveCount(1);
        $events[0]->shouldBeAnInstanceOf(RedirectUriWasAdded::class);
    }

    function it_can_remove_a_redirect_uri()
    {
        $uri = 'http://example.org';
        $this->addRedirectUri($uri)->shouldBe($this->getWrappedObject());
        $this->releaseEvents();
        $this->removeRedirectUri($uri)->shouldBe($this->getWrappedObject());
        $this->redirectUri()->shouldBe([]);
        $events = $this->releaseEvents();
        $events->shouldHaveCount(1);
        $events[0]->shouldBeAnInstanceOf(RedirectUriWasRemoved::class);
    }

    function it_can_change_its_details()
    {
        $newClientId = new ClientId('Another');
        $name = 'changed name';
        $secret = 'other-secret-given';
        $this->releaseEvents();
        $this->changeDetails($newClientId, $name, $secret)->shouldBe($this->getWrappedObject());
        $this->clientId()->shouldBe($newClientId);
        $this->name()->shouldBe($name);
        $this->secret()->shouldBe($secret);
        $events = $this->releaseEvents();
        $events->shouldHaveCount(1);
        $events[0]->shouldBeAnInstanceOf(ClientDetailsHasChanged::class);
    }
}
