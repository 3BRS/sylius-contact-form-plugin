<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusContactFormPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('three_brs_sylius_contact_form');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('send_manager_mail')->defaultFalse()->end()
                ->booleanNode('send_customer_mail')->defaultFalse()->end()
                ->booleanNode('name_required')->defaultFalse()->end()
                ->booleanNode('phone_required')->defaultFalse()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
