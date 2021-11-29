<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusContactFormPlugin\Behat\Pages\Admin\Message;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

final class ShowPage extends SymfonyPage implements ShowPageInterface
{
    public function getRouteName(): string
    {
        return 'threebrs_sylius_admin_contact_show';
    }

    public function sendMessage(): void
    {
        $this->getElement('send_message')->click();
    }

    public function addMessage(): void
    {
        $this->getElement('message')->setValue('message');
    }

    public function showMessage(): void
    {
        $lastM = $this->getElement('order_message')->getText();
        if (empty($lastM)) {
            throw new \RuntimeException(sprintf('no message found'));
        }
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'send_message' => '#formSubmitButton',
            'message' => '#threebrs_sylius_contact_answer_form_message',
            'order_message' => '.messageAnswer',
        ]);
    }
}
