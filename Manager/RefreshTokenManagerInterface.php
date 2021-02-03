<?php

declare(strict_types=1);

namespace TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Manager;

use TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Model\RefreshToken;

/**
 * @method int clearRevoked() not defining this method is deprecated since version 3.2
 */
interface RefreshTokenManagerInterface
{
    public function find(string $identifier): ?RefreshToken;

    public function save(RefreshToken $refreshToken): void;

    public function clearExpired(): int;
}
