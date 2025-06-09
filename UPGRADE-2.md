# UPGRADE from Sylius 1.14 to Sylius 2.0

## File Location Changes

* Moved config/ dir. out of src/ and into plugin root dir.
* Moved templates/ dir. out of src/ and into plugin root dir.
* Moved translations/ dir. out of src/ and into plugin root dir.

## Config Changes 

* The main config file is now located at: `config/config.yml`

* Routing configuration:

    * Main routing file: config/routing.yml
    * Routes themselves are defined in: config/routes/


## Twig Hooks

Templates are now rendered using Twig hooks, which is the standard in Sylius 2:

* **Admin**

    * [show_message_hooks.yaml](https://github.com/3BRS/sylius-contact-form-plugin/blob/sylius_2_upgrade_AK/src/config/app/twig_hooks/admin/show_message_hooks.yaml) contains config for Twig hooks used in Admin

* **Shop**

    * [messages_hooks.yaml](https://github.com/3BRS/sylius-contact-form-plugin/blob/sylius_2_upgrade_AK/src/config/app/twig_hooks/shop/messages_hooks.yaml) contains config for Twig hooks used in Shop
