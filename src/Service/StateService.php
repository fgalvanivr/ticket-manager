<?php

namespace App\Service;

use Symfony\Component\Workflow\Registry;
use App\Entity\Ticket;
use Symfony\Component\Workflow\Exception\TransitionException;

class StateService
{
    const WORKFLOW_NAME = 'ticket_managing';

    private $workflows;

    public function __construct(Registry $workflows)
    {
        $this->workflows = $workflows;
    }

    public function initialize(Ticket $ticket) {
        $workflow = $this->workflows->get($ticket, self::WORKFLOW_NAME);

        $workflow->getMarking($ticket);
        
        return true;
    }

    public function assignTicket(Ticket $ticket) {
        $workflow = $this->workflows->get($ticket, self::WORKFLOW_NAME);

        try {
            $workflow->apply($ticket, 'assign_ticket');
        } catch (TransitionException $ex) {
            return false;
        }
        return true;
    }

    public function closeTicket(Ticket $ticket) {
        $workflow = $this->workflows->get($ticket, self::WORKFLOW_NAME);

        try {
            $workflow->apply($ticket, 'close_ticket');
        } catch (TransitionException $ex) {
            return false;
        }
        return true;
    }
}
