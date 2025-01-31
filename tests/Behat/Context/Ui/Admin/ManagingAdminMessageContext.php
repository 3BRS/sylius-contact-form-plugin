<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusContactFormPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Sylius\Behat\NotificationType;
use Sylius\Behat\Service\NotificationCheckerInterface;
use Tests\ThreeBRS\SyliusContactFormPlugin\Behat\Pages\Admin\Message\ShowPageInterface;
use ThreeBRS\SyliusContactFormPlugin\Entity\ContactFormMessageInterface;
use ThreeBRS\SyliusContactFormPlugin\Repository\ContactFormMessageRepository;

final class ManagingAdminMessageContext implements Context
{
    public function __construct(private ShowPageInterface $showPage, private NotificationCheckerInterface $notificationChecker, private ContactFormMessageRepository $contactFormMessageRepository)
    {
    }

    /**
     * @When I view the summary of the message
     */
    public function iViewTheSummaryOfTheMessage()
    {
        $message = $this->contactFormMessageRepository->createQueryBuilder('o')
            ->orderBy('o.createdAt', 'desc')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

        assert($message instanceof ContactFormMessageInterface);

        $this->showPage->open(['id' => $message->getId()]);
    }

    /**
     * @When I write an answer message
     */
    public function iWriteAnAnswerMessage()
    {
        $this->showPage->addMessage();
    }

    /**
     * @When I send the answer message
     */
    public function iSendTheAnswerMessage(): void
    {
        $this->showPage->sendMessage();
    }

    /**
     * @Then I should be notified that the message as been created
     */
    public function iShouldBeNotifiedThatTheMessageAsBeenCreated(): void
    {
        $this->notificationChecker->checkNotification(
            'The message was successfully sent.',
            NotificationType::success()
        );
    }

    /**
     * @Then I see the message created
     */
    public function iSeeTheMessageCreated()
    {
        $this->showPage->showMessage();
    }
}
