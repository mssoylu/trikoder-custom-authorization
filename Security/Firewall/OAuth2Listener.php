<?php

declare(strict_types=1);

namespace TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Security\Firewall;

use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Security\Authentication\Token\OAuth2Token;
use TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Security\Authentication\Token\OAuth2TokenFactory;
use TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Security\Exception\InsufficientScopesException;
use TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Security\Exception\Oauth2AuthenticationFailedException;

final class OAuth2Listener
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var AuthenticationManagerInterface
     */
    private $authenticationManager;

    /**
     * @var HttpMessageFactoryInterface
     */
    private $httpMessageFactory;

    /**
     * @var OAuth2TokenFactory
     */
    private $oauth2TokenFactory;

    /**
     * @var string
     */
    private $providerKey;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthenticationManagerInterface $authenticationManager,
        HttpMessageFactoryInterface $httpMessageFactory,
        OAuth2TokenFactory $oauth2TokenFactory,
        string $providerKey
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->httpMessageFactory = $httpMessageFactory;
        $this->oauth2TokenFactory = $oauth2TokenFactory;
        $this->providerKey = $providerKey;
    }

    public function __invoke(RequestEvent $event)
    {
        $request = $this->httpMessageFactory->createRequest($event->getRequest());

        if (!$request->hasHeader('Authorization')) {
            return;
        }

        try {
            /** @var OAuth2Token $authenticatedToken */
            $authenticatedToken = $this->authenticationManager->authenticate($this->oauth2TokenFactory->createOAuth2Token($request, null, $this->providerKey));
        } catch (AuthenticationException $e) {
            throw new Oauth2AuthenticationFailedException($e->getMessage(), 401, $e);
        }

        if (!$this->isAccessToRouteGranted($event->getRequest(), $authenticatedToken)) {
            throw InsufficientScopesException::create($authenticatedToken);
        }

        $this->tokenStorage->setToken($authenticatedToken);
    }

    private function isAccessToRouteGranted(Request $request, OAuth2Token $token): bool
    {
        $routeScopes = $request->attributes->get('oauth2_scopes', []);

        if (empty($routeScopes)) {
            return true;
        }

        $tokenScopes = $token
            ->getAttribute('server_request')
            ->getAttribute('oauth_scopes');

        /*
         * If the end result is empty that means that all route
         * scopes are available inside the issued token scopes.
         */
        return empty(
            array_diff(
                $routeScopes,
                $tokenScopes
            )
        );
    }
}
