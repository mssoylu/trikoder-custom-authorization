<?php

declare(strict_types=1);

namespace TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Manager\Doctrine;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Manager\RefreshTokenManagerInterface;
use TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Model\RefreshToken;

final class RefreshTokenManager implements RefreshTokenManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function find(string $identifier): ?RefreshToken
    {
        return $this->entityManager->find(RefreshToken::class, $identifier);
    }

    /**
     * {@inheritdoc}
     */
    public function save(RefreshToken $refreshToken): void
    {
        $this->entityManager->persist($refreshToken);
        $this->entityManager->flush();
    }

    public function clearExpired(): int
    {
        return $this->entityManager->createQueryBuilder()
            ->delete(RefreshToken::class, 'rt')
            ->where('rt.expiry < :expiry')
            ->setParameter('expiry', new DateTimeImmutable())
            ->getQuery()
            ->execute();
    }

    public function clearRevoked(): int
    {
        return $this->entityManager->createQueryBuilder()
            ->delete(RefreshToken::class, 'rt')
            ->where('rt.revoked = true')
            ->getQuery()
            ->execute();
    }
}
