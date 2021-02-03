<?php

declare(strict_types=1);

namespace TrikoderCustomAuthorization\Bundle\OAuth2Bundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use TrikoderCustomAuthorization\Bundle\OAuth2Bundle\DependencyInjection\Security\OAuth2Factory;
use TrikoderCustomAuthorization\Bundle\OAuth2Bundle\DependencyInjection\TrikoderOAuth2Extension;

final class TrikoderOAuth2Bundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $this->configureDoctrineExtension($container);
        $this->configureSecurityExtension($container);
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new TrikoderOAuth2Extension();
    }

    private function configureSecurityExtension(ContainerBuilder $container): void
    {
        /** @var SecurityExtension $extension */
        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new OAuth2Factory());
    }

    private function configureDoctrineExtension(ContainerBuilder $container): void
    {
        $container->addCompilerPass(
            DoctrineOrmMappingsPass::createXmlMappingDriver(
                [
                    realpath(__DIR__ . '/Resources/config/doctrine/model') => 'TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Model',
                ],
                [
                    'trikoder.custom.authorization.oauth2.persistence.doctrine.manager',
                ],
                'trikoder.custom.authorization.oauth2.persistence.doctrine.enabled',
                [
                    'TrikoderOAuth2Bundle' => 'TrikoderCustomAuthorization\Bundle\OAuth2Bundle\Model',
                ]
            )
        );
    }
}
