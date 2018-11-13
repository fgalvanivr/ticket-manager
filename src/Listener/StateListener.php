<?php
namespace App\Listener;

use App\Entity\User;
use App\Service\NotificationService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class StateListener implements EventSubscriberInterface
{
    private $em;

    private $notificationService;

    private $security;

    private $currentUser;

    public function __construct(ObjectManager $em, NotificationService $notificationService, Security $security)
    {
        $this->em = $em;
        $this->notificationService = $notificationService;
        $this->security = $security;
        $this->currentUser = $security->getUser();
    }

    public function onEnterCreate(Event $event)
    {
        $ticket = $event->getSubject();

        $emails = $this->getAllAdminsEmail();

        $this->notificationService->sendNotification($ticket,join(',',$emails),'new ticket');
    }

    private function getAllAdminsEmail() {
        $users = $this->em->getRepository(User::class)->findAllAdmin();
        $emails = [];
        foreach ($users as $user) {
            $emails[] = $user->getEmail();
        }
        return $emails;
    }

    public function onAddMessage(Event $event)
    {
        $ticket = $event->getSubject();

        $to = null;
        if (!empty($ticket->getAssignedTo()) && $ticket->getCreatedBy() == $this->currentUser) {
            $to = $ticket->getAssignedTo()->getEmail();
        }
        elseif (!empty($ticket->getCreatedBy()) && $ticket->getAssignedTo() == $this->currentUser) {
            $to = $ticket->getCreatedBy()->getEmail();
        }
        else {
            return;
        }
        $this->notificationService->sendNotification($ticket,$to,'new message');
    }

    public function onCloseTicket(Event $event)
    {
        $ticket = $event->getSubject();

        $to = null;
        if (empty($ticket->getAssignedTo())) {
            $emails = $this->getAllAdminsEmail();
            $to = join(',',$emails);
        }
        elseif (!empty($ticket->getAssignedTo()) && $ticket->getCreatedBy() == $this->currentUser) {
            $to = $ticket->getAssignedTo()->getEmail();
        } elseif (!empty($ticket->getCreatedBy()) && $ticket->getAssignedTo() == $this->currentUser) {
            $to = $ticket->getCreatedBy()->getEmail();
        } else {
            return;
        }
        $this->notificationService->sendNotification($ticket,$to,'close ticket');
    }

    public function onEnterClosed(Event $event)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.ticket_managing.enter.new' => 'onEnterCreate',
            'workflow.ticket_managing.transition.add_message' => 'onAddMessage',
            'workflow.ticket_managing.transition.close_ticket' => 'onCloseTicket',
        ];
    }
}