<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusContactFormPlugin\Controller;

use Doctrine\ORM\EntityManagerInterface;
use ReCaptcha\ReCaptcha;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ShopUser;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use ThreeBRS\SyliusContactFormPlugin\Entity\ContactFormMessage;
use ThreeBRS\SyliusContactFormPlugin\Form\Type\ContactFormMessageType;
use ThreeBRS\SyliusContactFormPlugin\Model\ContactFormSettingsProviderInterface;
use Twig\Environment;

class ContactFormController
{
    /** @var ContactFormSettingsProviderInterface */
    private $contactFormSettings;
    /** @var Environment */
    private $templatingEngine;
    /** @var TranslatorInterface */
    private $translator;
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var SenderInterface */
    private $mailer;
    /** @var RouterInterface */
    private $router;
    /** @var FlashBagInterface */
    private $flashBag;
    /** @var FormFactoryInterface */
    private $builder;
    /** @var ChannelContextInterface */
    private $channelContext;
    /** @var TokenStorageInterface */
    private $token;
    /** @var string */
    private $recaptchaPublic;
    /** @var string */
    private $recaptchaSecret;

    public function __construct(
        ContactFormSettingsProviderInterface $contactFormSettings,
        Environment $templatingEngine,
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager,
        SenderInterface $mailer,
        RouterInterface $router,
        FlashBagInterface $flashBag,
        FormFactoryInterface $builder,
        ChannelContextInterface $channelContext,
        TokenStorageInterface $tokenStorage,
        string $recaptchaPublic,
        string $recaptchaSecret
    ) {
        $this->templatingEngine = $templatingEngine;
        $this->translator = $translator;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->router = $router;
        $this->flashBag = $flashBag;
        $this->builder = $builder;
        $this->channelContext = $channelContext;
        $this->token = $tokenStorage;
        $this->recaptchaPublic = $recaptchaPublic;
        $this->recaptchaSecret = $recaptchaSecret;
        $this->contactFormSettings = $contactFormSettings;
    }

    public function requestAction(Request $request): Response
    {
        $contactFormMessage = new ContactFormMessage();

        $token = $this->token->getToken();
        if ($token !== null) {
            $shopUser = $token->getUser();
            if ($shopUser instanceof ShopUser) {
                $customer = $shopUser->getCustomer();
                assert($customer instanceof CustomerInterface);
                $contactFormMessage->setCustomer($customer);
                $contactFormMessage->setEmail($customer->getEmail());
                $contactFormMessage->setCustomerName($customer->getFullName());
            }
        }

        $form = $this->builder->create(ContactFormMessageType::class, $contactFormMessage, [
            'action' => $this->router->generate('sylius_shop_contact_request'),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($this->recaptchaPublic !== null && $this->recaptchaSecret !== null && $this->recaptchaPublic !== '' && $this->recaptchaSecret !== 'null' && $form->isValid()) {
                $recaptcha = new ReCaptcha($this->recaptchaSecret);
                $resp = $recaptcha->verify((string) $request->request->get('g-recaptcha-response'), $request->getClientIp());
                if (!$resp->isSuccess()) {
                    $form->addError(new FormError($this->translator->trans('threebrs_sylius_contact_form_plugin.error.recaptcha')));
                }
            }

            if ($form->isValid()) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];

                $contactFormMessage->setIp($ip);
                $contactFormMessage->setUserAgent($_SERVER['HTTP_USER_AGENT']);
                $contactFormMessage->setCreatedAt(new \DateTime());

                $this->entityManager->persist($contactFormMessage);
                $this->entityManager->flush();

                $channel = $this->channelContext->getChannel();
                assert($channel instanceof ChannelInterface);

                $contactEmail = $channel->getContactEmail();
                if ($contactEmail !== null && $this->contactFormSettings->isSendManager() !== false) {
                    $this->mailer->send('threebrs_sylius_contact_form_admin_notice_email', [$contactEmail], ['contact' => $contactFormMessage]);
                }
                if ($this->contactFormSettings->isSendCustomer() !== false) {
                    $this->mailer->send('threebrs_sylius_contact_form_contact_form_email', [$contactFormMessage->getEmail()], ['contact' => $contactFormMessage]);
                }

                $this->flashBag->add('success', $this->translator->trans('threebrs_sylius_contact_form_plugin.success'));

                return new RedirectResponse($this->router->generate('sylius_shop_contact_request'));
            }
            $this->flashBag->add('error', $this->translator->trans('threebrs_sylius_contact_form_plugin.error.form'));
        }

        return new Response($this->templatingEngine->render('@ThreeBRSSyliusContactFormPlugin/Shop/contactPage.html.twig', [
            'form' => $form->createView(),
            'key' => $this->recaptchaPublic,
        ]));
    }
}
