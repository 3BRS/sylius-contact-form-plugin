<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use Tests\ThreeBRS\SyliusContactFormPlugin\Behat\Context\Setup\MessageContext;
use Tests\ThreeBRS\SyliusContactFormPlugin\Behat\Context\Ui\Admin\ManagingAdminMessageContext;
use Tests\ThreeBRS\SyliusContactFormPlugin\Behat\Pages\Admin\Message\ShowPage;
use Tests\ThreeBRS\SyliusContactFormPlugin\Behat\Pages\Admin\Message\ShowPageInterface;
use ThreeBRS\SyliusContactFormPlugin\Repository\ContactFormMessageRepositoryInterface;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
            ->public();

    $services->set('sylius.behat.context.ui.admin.message_form', ManagingAdminMessageContext::class)
        ->args([
            service(ShowPageInterface::class),
            service('sylius.behat.notification_checker.admin'),
            service(ContactFormMessageRepositoryInterface::class),
        ]);

    $services->set(ShowPageInterface::class, ShowPage::class)
        ->public()
        ->parent('sylius.behat.symfony_page');

    $services->set('threebrs_sylius.behat.context.setup.message', MessageContext::class)
        ->args([
            service('doctrine.orm.entity_manager'),
        ]);
};
