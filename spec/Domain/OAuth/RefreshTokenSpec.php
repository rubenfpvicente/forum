<?php

namespace spec\App\Domain\OAuth;

use App\Domain\OAuth\RefreshToken;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use PhpSpec\ObjectBehavior;

class RefreshTokenSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(RefreshToken::class);
    }

    function its_an_oauth2_refresh_token()
    {
        $this->shouldBeAnInstanceOf(RefreshTokenEntityInterface::class);
    }
}
