<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusContactFormPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

/** @extends AbstractType<mixed> */
class ContactFormMessageAnswerType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message', TextareaType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'style' => 'width: 100%; height: 100%;'],
            ]);
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix()
    {
        return 'threebrs_sylius_contact_answer_form';
    }
}
