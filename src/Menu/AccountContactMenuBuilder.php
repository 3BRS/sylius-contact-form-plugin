<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusContactFormPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

class AccountContactMenuBuilder
{
    public function buildMenu(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $menu
            ->addChild('new', [
                'route' => 'threebrs_sylius_contact_form_shop_account_message_index',
            ])
            ->setLabel('threebrs_sylius_contact_form_plugin.title.customer.index')
            ->setLabelAttribute('icon', 'comments')
        ;
    }
}
