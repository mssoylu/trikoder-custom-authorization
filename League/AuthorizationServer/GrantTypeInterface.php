<?php

declare(strict_types=1);

namespace TrikoderCustomAuthorization\Bundle\OAuth2Bundle\League\AuthorizationServer;

use DateInterval;
use League\OAuth2\Server\Grant\GrantTypeInterface as LeagueGrantTypeInterface;

interface GrantTypeInterface extends LeagueGrantTypeInterface
{
    public function getAccessTokenTTL(): ?DateInterval;
}
