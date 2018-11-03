<?php

namespace App\Service;

use App\Entity\Ticket;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class TicketService
{
    private $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    public function getTickets()
    {
        // TODO ACL, each registered user can manage only his tickets

        $tickets = $this->em->getRepository(Ticket::class)->findAll();
        
        return $tickets;
    }


    public function create() {
        // TODO ACL , registered user

/*        $em = $this->getDoctrine()->getManager();

        $ticket = new Ticket();


        $em->persist($product);
        $em->flush();*/
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
