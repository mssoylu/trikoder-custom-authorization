<?php

declare(strict_types=1);

namespace TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Manager;

use TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Model\Scope;

interface ScopeManagerInterface
{
    public function find(string $identifier): ?Scope;

    public function save(Scope $scope): void;
}
