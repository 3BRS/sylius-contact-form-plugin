<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusContactFormPlugin\Behat\Pages\Admin\Message;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

final class ShowPage extends SymfonyPage implements ShowPageInterface
{
    private const MESSAGE_VALUE = 'Some message';

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
        $this->getElement('message')->setValue(self::MESSAGE_VALUE);
    }

    public function showMessage(): void
    {
        $lastMessage = $this->getElement('order_message')->getText();
        if (empty($lastMessage)) {
            throw new \RuntimeException('no message found');
        }
        if (!str_contains($lastMessage, self::MESSAGE_VALUE)) {
            throw new \RuntimeException(
                sprintf('Expected message with content "%s" but got "%s"', self::MESSAGE_VALUE, $lastMessage),
            );
        }
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'send_message'  => '#formSubmitButton',
            'message'       => '#threebrs_sylius_contact_answer_form_message',
            'order_message' => '.messageAnswer',
        ]);
    }
}
