<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusContactFormPlugin\Model;

class ContactFormSettingsProvider implements ContactFormSettingsProviderInterface
{
    /** @var bool */
    private $nameRequired;
    /** @var bool */
    private $phoneRequired;
    /** @var bool */
    private $sendManager;
    /** @var bool */
    private $sendCustomer;

    public function __construct(array $config)
    {
        $this->nameRequired = $config['name_required'];
        $this->phoneRequired = $config['phone_required'];
        $this->sendManager = $config['send_manager_mail'];
        $this->sendCustomer = $config['send_customer_mail'];
    }

    public function isSendCustomer(): bool
    {
        return $this->sendCustomer;
    }

    public function isNameRequired(): bool
    {
        return $this->nameRequired;
    }

    public function isPhoneRequired(): bool
    {
        return $this->phoneRequired;
    }

    public function isSendManager(): bool
    {
        return $this->sendManager;
    }
}
