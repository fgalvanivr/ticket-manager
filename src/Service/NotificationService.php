<?php

namespace App\Service;

use App\Entity\Ticket;
use Symfony\Component\HttpFoundation\RequestStack;

class NotificationService
{
    private $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function sendNotification(Ticket $ticket, $recipients, $message) {

        $this->sendEmail($ticket,$recipients,$message);
        if ($ticket->getFlagSms()) {
            $this->sendSMS($ticket,$recipients,$message);
        }
        if ($ticket->getFlagPush()) {
            $this->sendPushNotification($ticket,$recipients,$message);
        }
    }
    
    public function sendEmail(Ticket $ticket, $recipients, $message) {
        $this->request->getSession()->getFlashBag()->add('notice', 'Ticket '.$ticket->getId().' send EMAIL to '.$recipients.' : '.$message);
    }

    public function sendSMS(Ticket $ticket, $recipients, $message) {
        $this->request->getSession()->getFlashBag()->add('notice', 'Ticket '.$ticket->getId().' send SMS to '.$recipients.' : '.$message);
    }

    public function sendPushNotification(Ticket $ticket, $recipients, $message) {
        $this->request->getSession()->getFlashBag()->add('notice', 'Ticket '.$ticket->getId().' send PUSH NOTIFICATION to '.$recipients.' : '.$message);
    }
}
