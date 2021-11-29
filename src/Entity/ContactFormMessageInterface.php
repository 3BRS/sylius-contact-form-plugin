<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusContactFormPlugin\Entity;

use Sylius\Component\Core\Model\CustomerInterface;

interface ContactFormMessageInterface
{
    public function getCustomerDescriptor(): ?string;

    public function getCustomerName(): ?string;

    public function setCustomerName(?string $customerName): void;

    public function getEmail(): ?string;

    public function setEmail(?string $email): void;

    public function getPhone(): ?string;

    public function setPhone(?string $phone): void;

    public function getMessage(): ?string;

    public function setMessage(?string $message): void;

    public function getCreatedAt(): ?\DateTime;

    public function setCreatedAt(?\DateTime $createdAt): void;

    public function getCustomer(): ?CustomerInterface;

    public function setCustomer(?CustomerInterface $customer): void;

    public function getUserAgent(): ?string;

    public function setUserAgent(?string $userAgent): void;

    public function getIp(): ?string;

    public function setIp(?string $ip): void;
}
