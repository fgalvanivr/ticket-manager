<?php
namespace App\Listener;

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

    public function onCreate(Event $event)
    {
        die('xxx');
        $ticket = $event->getSubject();

        $this->notificationService->sendEmail($ticket);
    }

    public function onAssign(Event $event)
    {
        die('xxx');
        $ticket = $event->getSubject();

        $this->notificationService->sendEmail($ticket);
    }

    public function onClose(Event $event)
    {
        $ticket = $event->getSubject();

        $this->notificationService->sendEmail($ticket);
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.ticket_managing.enter.new' => 'onCreate',
            'workflow.ticket_managing.enter.assign' => 'onAssign',
            'workflow.ticket_managing.enter.close' => 'onClose',
        ];
    }
}