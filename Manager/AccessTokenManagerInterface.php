<?php

declare(strict_types=1);

namespace TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Manager;

use TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Model\AccessToken;

/**
 * @method int clearRevoked() not defining this method is deprecated since version 3.2
 */
interface AccessTokenManagerInterface
{
    public function find(string $identifier): ?AccessToken;

    public function save(AccessToken $accessToken): void;

    public function clearExpired(): int;
}
