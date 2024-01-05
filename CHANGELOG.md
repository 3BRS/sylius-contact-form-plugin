# CHANGELOG

## v1.1.0 (2023-01-05)

#### Details

- Add support for Sylius 1.11|1.12, Symfony ^5.4|^6.0, PHP ^8.0
- Drop support for Sylius <= 1.10 and consequentially for Symfony <= 5.3, <= PHP 7.4

## v1.0.0 (2021-11-29)

### Details

- Support for Sylius 1.8|1.9|1.10, Symfony ^4.4|^5.2, PHP ^7.3|^8.0
- Change namespace from `MangoSylius\SyliusContactFormPlugin` to `ThreeBRS\SyliusContactFormPlugin`
- Change table name from `mangoweb_contact_form` to `threebrs_sylius_contact_form`
- Change table name from `mangoweb_contact_form_message_answer` to `threebrs_sylius_contact_form_message_answer`
- Change config name from `mango_sylius_contact_form` to `three_brs_sylius_contact_form`
- ⚠️ *BC break: Version 1.0.0 is NOT compatible with previous versions due to **namespace change***
