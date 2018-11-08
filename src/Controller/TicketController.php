<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Ticket;
use App\Entity\Message;
use App\Form\MessageType;
use App\Entity\User;
use App\Service\TicketService;
use App\Service\StateService;

class TicketController extends AbstractController
{
    /**
     * @Route("/ticket", name="ticket")
     */
    public function index(TicketService $ticketService)
    {
        // TODO ACL, each registered user can manage only his tickets

        $tickets = $ticketService->getTickets();
        return $this->render('ticket/index.html.twig', [
            'tickets' => $tickets,
        ]);
    }

    /**
     * @Route("/ticket/new", name="create_ticket")
     */
    public function create(Request $request, TicketService $ticketService, StateService $stateService) {
        // TODO ACL , registered user

        $message = new Message();

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            $ticket = $ticketService->create($message);

            $check = $stateService->initialize($ticket);
            if (!$check) {
                die('Initializing state failed');
            }

            return $this->redirectToRoute('ticket');
        }

        return $this->render('ticket/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ticket/{id}/edit", name="edit_ticket")
     */
    public function edit(Request $request, TicketService $ticketService, Ticket $ticket) {

        $message = new Message();

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            $ticket = $ticketService->edit($ticket, $message);

            return $this->redirectToRoute('ticket');
        }

        return $this->render('ticket/edit.html.twig', [
            'form' => $form->createView(),
            'ticket' => $ticket
        ]);
    }

    /**
     * @Route("/ticket/{id}/delete", name="delete_ticket")
     */
    public function delete(Ticket $ticket) {

    }

    /**
     * @Route("/ticket/{id}/reply", name="reply_ticket")
     */
    public function reply(Ticket $ticket) {
        // TODO ACL , registered user can reply only his tickets
        // TODO ACL , admin user can reply only his tickets or new tickets
    }

    /**
     * @Route("/ticket/{id}/close", name="close_ticket")
     */
    public function close(Ticket $ticket) {
        // TODO ACL , registered user can close only his tickets
        // TODO ACL , admin user can close only his tickets or new tickets
    }

    /**
     * @Route("/ticket/{id}/assign/{userid}", name="assign_ticket")
     */
    public function assign(Ticket $ticket, User $user) {
        // TODO ACL , admin user can take a new ticket, and can transfer it to another admin user
    }
}
