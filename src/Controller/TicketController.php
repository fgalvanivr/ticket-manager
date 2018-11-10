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
    public function create(Request $request, TicketService $ticketService) {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $message = new Message();

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            $ticket = $ticketService->create($message);

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

        $this->denyAccessUnlessGranted('edit', $ticket);

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
    public function delete(TicketService $ticketService, Ticket $ticket) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $ticketService->delete($ticket);

        return $this->redirectToRoute('ticket');
    }

    /**
     * @Route("/ticket/{id}/close", name="close_ticket")
     */
    public function close(TicketService $ticketService, Ticket $ticket) {
        $this->denyAccessUnlessGranted('close', $ticket);

        $ticketService->close($ticket);

        return $this->redirectToRoute('ticket');
    }

    /**
     * @Route("/ticket/{id}/assign", name="assign_ticket")
     */
    public function assign(TicketService $ticketService, Ticket $ticket) {
        $this->denyAccessUnlessGranted('assign', $ticket);

        $ticketService->assign($ticket);

        return $this->redirectToRoute('ticket');
    }
}
