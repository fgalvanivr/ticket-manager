<?php

namespace App\Service;

use App\Entity\Ticket;

class NotificationService
{
    public function __construct()
    {

    }

    public function sendEmail(Ticket $ticket) {
        echo "send email ".$ticket->getId().PHP_EOL;
    }

    public function sendSMS(Ticket $ticket) {
        echo "send SMS ".$ticket->getId().PHP_EOL;
    }

    public function sendPushNotification(Ticket $ticket) {
        echo "send Push notification ".$ticket->getId().PHP_EOL;
    }
}
