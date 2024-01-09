<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusContactFormPlugin\Behat\Pages\Admin\Message;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use Webmozart\Assert\Assert;

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
        $this->getElement('message')->setValue($this->getMessageText());
    }

    private function getMessageText(): string
    {
        return 'some message';
    }

    public function showMessage(): void
    {
        $lastMessage = $this->getElement('order_message')->getText();
        if (empty($lastMessage)) {
            throw new \RuntimeException(sprintf('no message found'));
        }
        Assert::endsWith(trim($lastMessage), trim($this->getMessageText()));
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
