<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusContactFormPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use ThreeBRS\SyliusContactFormPlugin\Model\ContactFormSettingsProviderInterface;

/** @extends AbstractType<mixed> */
class ContactFormMessageType extends AbstractType
{
    public function __construct(private ContactFormSettingsProviderInterface $contactFormSettings) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'threebrs_sylius_contact_form_plugin.email',
                'required' => true,
            ])
            ->add('message', TextareaType::class, [
                'label' => 'threebrs_sylius_contact_form_plugin.message',
                'required' => true,
            ]);

        if ($this->contactFormSettings->isNameRequired() !== false) {
            $builder
                ->add('customerName', TextType::class, [
                    'label' => 'threebrs_sylius_contact_form_plugin.customerName',
                    'constraints' => [
                        new NotBlank(),
                    ],
                    'required' => true,
                ]);
        } else {
            $builder
                ->add('customerName', TextType::class, [
                    'label' => 'threebrs_sylius_contact_form_plugin.customerName',
                    'constraints' => [
                        new NotBlank([
                            'allowNull' => true,
                        ]),
                    ],
                    'required' => false,
                ]);
        }

        if ($this->contactFormSettings->isPhoneRequired() !== false) {
            $builder
                ->add('phone', TelType::class, [
                    'label' => 'threebrs_sylius_contact_form_plugin.phone',
                    'constraints' => [
                        new NotBlank(),
                    ],
                    'required' => true,
                ]);
        } else {
            $builder
                ->add('phone', TelType::class, [
                    'label' => 'threebrs_sylius_contact_form_plugin.phone',
                    'constraints' => [
                        new NotBlank([
                            'allowNull' => true,
                        ]),
                    ],
                    'required' => false,
                ]);
        }
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix()
    {
        return 'threebrs_sylius_contact_form';
    }
}
