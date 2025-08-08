<p align="center">
    <a href="https://www.3brs.com" target="_blank">
        <img src="https://3brs1.fra1.cdn.digitaloceanspaces.com/3brs/logo/3BRS-logo-sylius-200.png"/>
    </a>
</p>
<h1 align="center">
Contact Form Plugin
<br />
    <a href="https://packagist.org/packages/3brs/sylius-contact-form-plugin" title="License" target="_blank">
        <img src="https://img.shields.io/packagist/l/3brs/sylius-contact-form-plugin.svg" />
    </a>
    <a href="https://packagist.org/packages/3brs/sylius-contact-form-plugin" title="Version" target="_blank">
        <img src="https://img.shields.io/packagist/v/3brs/sylius-contact-form-plugin.svg" />
    </a>
    <a href="https://circleci.com/gh/3BRS/sylius-contact-form-plugin" title="Build status" target="_blank">
        <img src="https://circleci.com/gh/3BRS/sylius-contact-form-plugin.svg?style=shield" />
    </a>
</h1>

## Features

* Extend contact form
* Add a ReCaptcha Verification to the contact form (only supports invisible ReCaptcha V2)
* Add Message administrative panel
    * conversation history
    * Possibility to respond instantly

<p align="center">
	<img src="https://github.com/3BRS/sylius-contact-form-plugin/blob/sylius_2_upgrade_AK/doc/contact_form.PNG?raw=true"/>
</p>
<p align="center">
	<img src="https://github.com/3BRS/sylius-contact-form-plugin/blob/sylius_2_upgrade_AK/doc/messages_management_admin_menu.PNG?raw=true"/>
</p>
<p align="center">
	<img src="https://github.com/3BRS/sylius-contact-form-plugin/blob/sylius_2_upgrade_AK/doc/messages_management_admin_answer_menu.PNG?raw=true"/>
</p>
<p align="center">
	<img src="https://github.com/3BRS/sylius-contact-form-plugin/blob/sylius_2_upgrade_AK/doc/messages_management_account_menu.PNG?raw=true"/>
</p>

## Installation

1. Run `composer require 3brs/sylius-contact-form-plugin`.
2. Register `\ThreeBRS\SyliusContactFormPlugin\ThreeBRSSyliusContactFormPlugin` in your Kernel.
    ```php
   // config/bundles.php
    ThreeBRS\SyliusContactFormPlugin\ThreeBRSSyliusContactFormPlugin::class => ['all' => true],
   ```
3. Add resource to `config/packages/_sylius.yaml`

    ```yaml
    imports:
         # ...
         - { resource: "@ThreeBRSSyliusContactFormPlugin/config/config.yml" }
    ```
   
4. Add routing to `config/routes.yaml`

    ```yaml

    threebrs_sylius_contact_form_plugin:
        resource: "@ThreeBRSSyliusContactFormPlugin/config/routing.yml"
    ```

5. Define parameters in `.env` file (or whenever you keep your environment variables):

    ```
    # Recaptcha public key setter for contact form
    GOOGLE_RECAPTCHA_SITE_KEY=
    # Recaptcha secret key setter for contact form
    GOOGLE_RECAPTCHA_SECRET=
    ```

6. Create and run doctrine database migrations.

For the guide how to use your own entity see [Sylius docs - Customizing Models](https://docs.sylius.com/the-customization-guide/customizing-models)

### Usage

* Parameters can be left empty if you want to run the plugin without recaptcha verification.
* The plugin is made to work with invisible V2 recaptcha, it is essential to select this version during their creation.
* The plugin defines the contact email of the channel (configurable in the `Channels` tab of the `configuration` section in the administration panel) as the manager's email address.

## Configuration
   ```yaml
    threebrs_sylius_contact_form_plugin:
        # Define if an email should be sent to the manager when contact form is send
          send_manager_mail: true/false
        # Define if an email should be sent to the customer when contact form is send (copy)
          send_customer_mail: true/false
        # Define 'name' field requirement in contact form
          name_required: true/false
        # Define 'phone' field requirement in contact form
          phone_required: true/false
   ```

## Usage

* Log into admin panel as administrator or account panel as registered customer
* Go into `Messages` section
* Select the conversation you want to answer to
* Write your reply message
* Click `Send` button below

## Development

### Setup

Initialize the development environment:

```bash
make init
```

This command installs dependencies, sets up the database, and prepares frontend assets (or follow related steps in Makefile).

### Usage

- Develop your plugin logic inside `/src`
- See `bin/` for useful dev tools

### Testing

After your changes you must ensure that the tests are still passing.

```bash
make tests
# Or run individual commands:
make static  # phpstan, ecs, lint
make behat   # behat tests
```

License
-------
This library is under the MIT license.

Credits
-------
Developed by [3BRS](https://3brs.com)<br>
Forked from [manGoweb](https://github.com/mangoweb-sylius/SyliusContactFormPlugin).
