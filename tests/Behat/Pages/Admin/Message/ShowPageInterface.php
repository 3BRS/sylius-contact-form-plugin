<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusContactFormPlugin\Behat\Pages\Admin\Message;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;

interface ShowPageInterface extends SymfonyPageInterface
{
    public function addMessage(): void;

    public function sendMessage(): void;

    public function showMessage(): void;
}
