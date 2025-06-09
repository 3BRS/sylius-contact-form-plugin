<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusContactFormPlugin\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\AdminUser;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use ThreeBRS\SyliusContactFormPlugin\Controller\Partials\GetFlashBagTrait;
use ThreeBRS\SyliusContactFormPlugin\Entity\ContactFormMessage;
use ThreeBRS\SyliusContactFormPlugin\Entity\ContactFormMessageAnswer;
use ThreeBRS\SyliusContactFormPlugin\Form\Type\ContactFormMessageAnswerType;
use ThreeBRS\SyliusContactFormPlugin\Repository\ContactFormMessageAnswerRepository;
use ThreeBRS\SyliusContactFormPlugin\Repository\ContactFormMessageRepository;
use Twig\Environment;

class ContactFormAdminController
{
    use GetFlashBagTrait;

    public function __construct(private Environment $templatingEngine, private TranslatorInterface $translator, private EntityManagerInterface $entityManager, private SenderInterface $mailer, private RouterInterface $router, private FormFactoryInterface $builder, private ContactFormMessageRepository $contactFormMessageRepository, private ContactFormMessageAnswerRepository $contactFormMessageAnswerRepository, private TokenStorageInterface $tokenStorage)
    {
    }

    public function showMessageAction(Request $request, int $id): Response
    {
        $contactFormMessage = $this->contactFormMessageRepository->find($id);
        $contactFormMessageAnswers = $this->contactFormMessageAnswerRepository->findBy(['contactFormMessage' => $id]);

        $contactFormMessageAnswer = new ContactFormMessageAnswer();
        $form = $this->builder->create(ContactFormMessageAnswerType::class, $contactFormMessageAnswer, [
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormMessage = $this->contactFormMessageRepository->find($id);
            assert($contactFormMessage instanceof ContactFormMessage);

            $token = $this->tokenStorage->getToken();
            assert($token && $token->getUser() instanceof AdminUser);

            $adminUser = $token->getUser();

            $contactFormMessageAnswer->setCreatedAt(new \DateTime());
            $contactFormMessageAnswer->setContactFormMessage($contactFormMessage);
            $contactFormMessageAnswer->setAdminUser($adminUser);

            $this->entityManager->persist($contactFormMessageAnswer);
            $this->entityManager->flush();

            $this->mailer->send('threebrs_sylius_contact_form_answer_email', [$contactFormMessage->getEmail()], ['contact' => $contactFormMessageAnswer]);

            $this->getFlashBag($request)->add('success', $this->translator->trans('threebrs_sylius_contact_form_plugin.success'));

            return new RedirectResponse($this->router->generate('threebrs_sylius_admin_contact_show', ['id' => $id]));
        }

        return new Response($this->templatingEngine->render('@ThreeBRSSyliusContactFormPlugin/Admin/show.html.twig', [
            'message' => $contactFormMessage,
            'answers' => $contactFormMessageAnswers,
            'form' => $form->createView(),
        ]));
    }
}
