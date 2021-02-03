<?php

declare(strict_types=1);

namespace TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Manager\InMemory;

use DateTimeImmutable;
use TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Manager\RefreshTokenManagerInterface;
use TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Model\RefreshToken;

final class RefreshTokenManager implements RefreshTokenManagerInterface
{
    /**
     * @var RefreshToken[]
     */
    private $refreshTokens = [];

    /**
     * {@inheritdoc}
     */
    public function find(string $identifier): ?RefreshToken
    {
        return $this->refreshTokens[$identifier] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function save(RefreshToken $refreshToken): void
    {
        $this->refreshTokens[$refreshToken->getIdentifier()] = $refreshToken;
    }

    public function clearExpired(): int
    {
        $count = \count($this->refreshTokens);

        $now = new DateTimeImmutable();
        $this->refreshTokens = array_filter($this->refreshTokens, static function (RefreshToken $refreshToken) use ($now): bool {
            return $refreshToken->getExpiry() >= $now;
        });

        return $count - \count($this->refreshTokens);
    }

    public function clearRevoked(): int
    {
        $count = \count($this->refreshTokens);

        $this->refreshTokens = array_filter($this->refreshTokens, static function (RefreshToken $refreshToken): bool {
            return !$refreshToken->isRevoked();
        });

        return $count - \count($this->refreshTokens);
    }
}
