<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusContactFormPlugin\Entity;

use Sylius\Component\Core\Model\AdminUserInterface;

interface ContactFormMessageAnswerInterface
{
    public function getMessage(): ?string;

    public function setMessage(?string $message): void;

    public function getCreatedAt(): ?\DateTime;

    public function setCreatedAt(?\DateTime $createdAt): void;

    public function getContactFormMessage(): ?ContactFormMessageInterface;

    public function setContactFormMessage(?ContactFormMessageInterface $contactFormMessage): void;

    public function getAdminUser(): ?AdminUserInterface;

    public function setAdminUser(?AdminUserInterface $adminUser): void;
}
