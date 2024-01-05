<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusContactFormPlugin\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use ThreeBRS\SyliusContactFormPlugin\Model\ContactFormSettingsProvider;

class ThreeBRSSyliusContactFormExtension extends AbstractResourceExtension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition(ContactFormSettingsProvider::class);
        $definition->addArgument($config);
    }
}
