<?php

declare(strict_types=1);

namespace TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Service;

use Symfony\Component\Security\Core\User\UserInterface;
use TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Model\Client;

/**
 * Service responsible for revoking credentials on client-level and user-level.
 * Credentials = access tokens, refresh tokens and authorization codes.
 *
 * @api
 */
interface CredentialsRevokerInterface
{
    public function revokeCredentialsForUser(UserInterface $user): void;

    public function revokeCredentialsForClient(Client $client): void;
}
