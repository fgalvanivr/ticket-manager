<?php

namespace App\Service;

use App\Entity\Ticket;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Security;
use App\Service\StateService;

class TicketService
{
    private $em;

    private $security;

    private $stateService;

    private $currentUser;

    private $ticketRepo;

    public function __construct(ObjectManager $em, Security $security, StateService $stateService)
    {
        $this->em = $em;
        $this->security = $security;
        $this->stateService = $stateService;
        $this->currentUser = $security->getUser();
        $this->ticketRepo = $em->getRepository(Ticket::class);
    }

    public function getTickets()
    {
        // TODO ACL, each registered user can manage only his tickets

        $tickets = [];
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $tickets = $this->ticketRepo->findAll();
        }
        else {
            $tickets = $this->ticketRepo->findByCreatedBy($this->currentUser);
        }
        
        return $tickets;
    }


    public function create(?Message $message) {

        $ticket = new Ticket();

        $author = $message->getCreatedBy();

        $ticket->setCreatedAt(new \DateTime());
        $ticket->setUpdatedAt(new \DateTime());
        $ticket->setCreatedBy($author);
        $ticket->addMessage($message);

        $this->em->persist($ticket);
        $this->em->flush();

        $check = $this->stateService->initialize($ticket);
        if (!$check) {
            die('Initializing state failed');
        }
        $this->em->flush();
        
        return $ticket;
    }

    public function edit(Ticket $ticket, Message $message) {

        $ticket->setUpdatedAt(new \DateTime());
        $ticket->addMessage($message);

        if (empty($ticket->getAssignedTo())) {
            if ($this->security->isGranted('ROLE_ADMIN')) {
                $ticket->setAssignedTo($this->currentUser);
                $this->stateService->assignTicket($ticket);
            }
        }
        else {
            $this->stateService->addMessage($ticket);
        }
        $this->em->flush();

        return $ticket;
    }

    public function delete(Ticket $ticket) {
        $this->em->remove($ticket);
        $this->em->flush();
    }

    public function close(Ticket $ticket) {
        $this->stateService->closeTicket($ticket);
        $this->em->flush();
    }

    public function assign(Ticket $ticket) {

        $this->assignTo($ticket, $this->currentUser);
    }

    public function assignTo(Ticket $ticket, User $user) {

        $ticket->setAssignedTo($user);

        $this->stateService->assignTicket($ticket);

        $this->em->flush();
    }
}
