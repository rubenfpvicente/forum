<?php

namespace spec\App\Domain\OAuth;

use App\Domain\OAuth\AccessToken;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use PhpSpec\ObjectBehavior;

class AccessTokenSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(AccessToken::class);
    }

    function its_an_OAuth2_access_token()
    {
        $this->shouldBeAnInstanceOf(AccessTokenEntityInterface::class);
    }
}
