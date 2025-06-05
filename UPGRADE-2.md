# UPGRADE from Sylius 1.14 to Sylius 2.0

## Config Changes

 I followed the upgrade instructions provided in the official [Sylius UPGRADE-2.0.md](https://github.com/Sylius/Sylius/blob/2.0/UPGRADE-2.0.md#upgrade-from-114-to-20) guide , specifically the section for upgrading from Sylius 1.14 to 2.0 to update necessary configuration to support upgrade to Sylius 2

## Twig Hooks

* **Admin**

    * [show_message_hooks.yaml](https://github.com/3BRS/sylius-contact-form-plugin/blob/sylius_2_upgrade_AK/src/config/app/twig_hooks/admin/show_message_hooks.yaml) contains config for Twig hooks used in Admin
* **Shop**

    * [messages_hooks.yaml](https://github.com/3BRS/sylius-contact-form-plugin/blob/sylius_2_upgrade_AK/src/config/app/twig_hooks/shop/messages_hooks.yaml) contains config for Twig hooks used in Shop

## Mailer Changes

* Replaced the usage of Sylius\Component\Mailer\Sender\SenderInterface with Symfony’s native MailerInterface and TemplatedEmail.

## File Location Changes

* Moved config/ dir. out of src/ and into plugin root dir.
* Moved templates/ dir. out of src/ and into plugin root dir.
* Moved translations/ dir. out of src/ and into plugin root dir.

## General Changes

* Changed ThreeBRSSyliusContactFormPlugin.php getPath() method from returning `return __DIR__;` to returning `return \dirname(__DIR__);` 

## tests/Application

* Used the new tests/Application for Sylius Version 2 provided by Sylius [Plugin Skeleton](https://github.com/Sylius/PluginSkeleton/tree/2.0/tests/Application)

## Template Changes

* Changed `{% extends '@SyliusShop/layout.html.twig' %}` to `{% extends '@SyliusShop/shared/layout/base.html.twig' %}`
* Removed the usage of Sematic-Ui and now using Bootstrap 5
* Removed deprecated `{{ sylius_grid_render(resources, '@SyliusShop/Grid/_default.html.twig') }}` and used custom grid renderer along side `@SyliusShop/shared/macro/grid/table.html.twig` and `@SyliusShop/shared/macro/pagination.html.twig`
* Changed `{% extends '@SyliusAdmin/layout.html.twig' %}` to `{% extends '@SyliusAdmin/shared/layout/base.html.twig' %}`
* Added base layout for Admin page without sylius resource, check ***templates/show.html.twig*
