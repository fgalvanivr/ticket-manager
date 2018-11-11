<?php

namespace App\Service;

use App\Entity\Ticket;
use Symfony\Component\HttpFoundation\RequestStack;

class NotificationService
{
    private $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();;
    }

    public function sendEmail(Ticket $ticket, $recipients) {
        $this->request->getSession()->getFlashBag()->add('notice', 'Ticket '.$ticket->getId().' send EMAIL to '.$recipients);
    }

    public function sendSMS(Ticket $ticket, $recipients) {
        $this->request->getSession()->getFlashBag()->add('notice', 'Ticket '.$ticket->getId().' send SMS to '.$recipients);
    }

    public function sendPushNotification(Ticket $ticket, $recipients) {
        $this->request->getSession()->getFlashBag()->add('notice', 'Ticket '.$ticket->getId().' send PUSH NOTIFICATION to '.$recipients);
    }
}
