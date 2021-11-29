<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusContactFormPlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use ThreeBRS\SyliusContactFormPlugin\Model\ContactFormSettingsProvider;

class ThreeBRSSyliusContactFormExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition(ContactFormSettingsProvider::class);
        $definition->addArgument($config);
    }
}
