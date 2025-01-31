<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusContactFormPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="threebrs_sylius_contact_form")
 */
#[ORM\Entity]
#[ORM\Table(name: 'threebrs_sylius_contact_form')]
class ContactFormMessage implements ResourceInterface, ContactFormMessageInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     *
     * @ORM\Id
     *
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', nullable: true)]
    protected $customerName;

    /**
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string|null
     * @Assert\NotBlank()
     *
     * @Assert\Email()
     */
    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Email]
    protected $email;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    protected $phone;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=false)
     * @Assert\NotBlank()
     */
    #[ORM\Column(type: 'text', nullable: false)]
    #[Assert\NotBlank]
    protected $message;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime")
     */
    #[ORM\Column(type: 'datetime')]
    protected $createdAt;

    /**
     * @var CustomerInterface|null
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Customer\Model\CustomerInterface")
     */
    #[ORM\ManyToOne(targetEntity: CustomerInterface::class)]
    protected $customer;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    #[ORM\Column(type: 'string', nullable: true)]
    protected $userAgent;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    #[ORM\Column(type: 'string', nullable: true)]
    protected $ip;

    public function getCustomerDescriptor(): ?string
    {
        return $this->customerName ?? $this->email;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function setCustomerName(?string $customerName): void
    {
        $this->customerName = $customerName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
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

    public function getCustomer(): ?CustomerInterface
    {
        return $this->customer;
    }

    public function setCustomer(?CustomerInterface $customer): void
    {
        $this->customer = $customer;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): void
    {
        $this->ip = $ip;
    }
}
