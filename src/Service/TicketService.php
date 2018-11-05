<?php

namespace App\Service;

use App\Entity\Ticket;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Security;

class TicketService
{
    private $em;

    private $security;

    public function __construct(ObjectManager $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function getTickets()
    {
        // TODO ACL, each registered user can manage only his tickets

        $tickets = $this->em->getRepository(Ticket::class)->findAll();
        
        return $tickets;
    }


    public function create(?Ticket $ticket) {
        // TODO ACL , registered user

        if (empty($ticket)) {
            $ticket = new Ticket();
        }

        $author = $this->security->getUser();

        $ticket->setCreatedAt(new \DateTime());
        $ticket->setUpdatedAt(new \DateTime());
        $ticket->setCreatedBy($author);

        $this->em->persist($ticket);
        $this->em->flush();
        
        return $ticket;
    }

    public function show(Ticket $ticket) {

    }

    public function edit(Ticket $ticket) {

    }

    public function delete(Ticket $ticket) {

    }

    public function reply(Ticket $ticket) {
        // TODO ACL , registered user can reply only his tickets
        // TODO ACL , admin user can reply only his tickets or new tickets
    }

    public function close(Ticket $ticket) {
        // TODO ACL , registered user can close only his tickets
        // TODO ACL , admin user can close only his tickets or new tickets
    }

    public function assign(Ticket $ticket, User $user) {
        // TODO ACL , admin user can take a new ticket, and can transfer it to another admin user
    }
}
