<?php

namespace App\Service;

use Symfony\Component\Workflow\Registry;
use App\Entity\Ticket;
use Symfony\Component\Workflow\Exception\TransitionException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Transition;

class StateService
{
    const WORKFLOW_NAME = 'ticket_managing';

    private $workflows;

    private $dispatcher;

    public function __construct(Registry $workflows, EventDispatcherInterface $dispatcher = null)
    {
        $this->workflows = $workflows;
        $this->dispatcher = $dispatcher;
    }

    public function initialize(Ticket $ticket) {
        $workflow = $this->workflows->get($ticket, self::WORKFLOW_NAME);

        $marking = $workflow->getMarking($ticket);

        $event = new Event($ticket, $marking, new Transition('initialize',null,'new'), $workflow);

        $this->dispatcher->dispatch('workflow.ticket_managing.enter.new', $event);

        return $ticket;
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

    public function addMessage(Ticket $ticket) {
        $workflow = $this->workflows->get($ticket, self::WORKFLOW_NAME);

        try {
            $workflow->apply($ticket, 'add_message');
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
