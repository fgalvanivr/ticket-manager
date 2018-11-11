<?php
namespace App\Listener;

use App\Entity\User;
use App\Service\NotificationService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class StateListener implements EventSubscriberInterface
{
    private $em;

    private $notificationService;

    public function __construct(ObjectManager $em, NotificationService $notificationService)
    {
        $this->em = $em;
        $this->notificationService = $notificationService;
    }

    public function onEnterCreate(Event $event)
    {
        $ticket = $event->getSubject();

        $users = $this->em->getRepository(User::class)->findAllAdmin();
        $emails = [];
        foreach ($users as $user) {
            $emails[] = $user->getEmail();
        }

        $this->notificationService->sendEmail($ticket,join(',',$emails));
    }

    public function onEnterWorking(Event $event)
    {
        $ticket = $event->getSubject();

        $this->notificationService->sendEmail($ticket,'xxx');
    }

    public function onEnterClosed(Event $event)
    {
        $ticket = $event->getSubject();

        $this->notificationService->sendEmail($ticket,'xxx');
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.ticket_managing.enter.new' => 'onEnterCreate',
            'workflow.ticket_managing.enter.working' => 'onEnterWorking',
            'workflow.ticket_managing.enter.closed' => 'onEnterClosed',
        ];
    }
}