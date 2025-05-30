<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusContactFormPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'threebrs_sylius_contact_form_message_answer')]
class ContactFormMessageAnswer implements ResourceInterface, ContactFormMessageAnswerInterface
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    protected ?int $id = null;

    #[ORM\Column(type: 'text', nullable: false)]
    #[Assert\NotBlank]
    protected ?string $message = null;

    #[ORM\Column(type: 'datetime')]
    protected ?\DateTime $createdAt = null;

    #[ORM\ManyToOne(targetEntity: ContactFormMessageInterface::class)]
    protected ?ContactFormMessageInterface $contactFormMessage = null;

    #[ORM\ManyToOne(targetEntity: AdminUserInterface::class)]
    protected ?AdminUserInterface $adminUser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getContactFormMessage(): ?ContactFormMessageInterface
    {
        return $this->contactFormMessage;
    }

    public function setContactFormMessage(?ContactFormMessageInterface $contactFormMessage): void
    {
        $this->contactFormMessage = $contactFormMessage;
    }

    public function getAdminUser(): ?AdminUserInterface
    {
        return $this->adminUser;
    }

    public function setAdminUser(?AdminUserInterface $adminUser): void
    {
        $this->adminUser = $adminUser;
    }
}
