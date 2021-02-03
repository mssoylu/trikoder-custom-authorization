<?php

declare(strict_types=1);

namespace TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Manager\InMemory;

use DateTimeImmutable;
use TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Manager\AccessTokenManagerInterface;
use TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Model\AccessToken;

final class AccessTokenManager implements AccessTokenManagerInterface
{
    /**
     * @var AccessToken[]
     */
    private $accessTokens = [];

    /**
     * {@inheritdoc}
     */
    public function find(string $identifier): ?AccessToken
    {
        return $this->accessTokens[$identifier] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function save(AccessToken $accessToken): void
    {
        $this->accessTokens[$accessToken->getIdentifier()] = $accessToken;
    }

    public function clearExpired(): int
    {
        $count = \count($this->accessTokens);

        $now = new DateTimeImmutable();
        $this->accessTokens = array_filter($this->accessTokens, static function (AccessToken $accessToken) use ($now): bool {
            return $accessToken->getExpiry() >= $now;
        });

        return $count - \count($this->accessTokens);
    }

    public function clearRevoked(): int
    {
        $count = \count($this->accessTokens);

        $this->accessTokens = array_filter($this->accessTokens, static function (AccessToken $accessToken): bool {
            return !$accessToken->isRevoked();
        });

        return $count - \count($this->accessTokens);
    }
}
