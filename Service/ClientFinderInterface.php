<?php

declare(strict_types=1);

namespace TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Service;

use TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Model\Client;

/**
 * @api
 */
interface ClientFinderInterface
{
    public function find(string $identifier): ?Client;
}
