<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusContactFormPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

class AdminContactMenuBuilder
{
    public function buildMenu(MenuBuilderEvent $event): void
    {
        $customer = $event
            ->getMenu()
            ->getChild('customers');

        if ($customer !== null) {
            $customer
                ->addChild('contact', [
                    'route' => 'threebrs_sylius_contact_form_plugin_admin_message_index',
                ])
                ->setName('threebrs_sylius_contact_form_plugin.ui.title')
                ->setLabelAttribute('icon', 'comments');
        }
    }
}
