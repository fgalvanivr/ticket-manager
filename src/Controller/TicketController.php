<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Ticket;
use App\Entity\Message;
use App\Form\MessageType;
use App\Form\AssignToType;
use App\Form\TicketType;
use App\Service\TicketService;

class TicketController extends AbstractController
{
    /**
     * @Route("/ticket", name="ticket")
     */
    public function index(TicketService $ticketService)
    {
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

        $ticket = new Ticket();
        $message = new Message();
        $ticket->addMessage($message);

        $form = $this->createForm(TicketType::class, $ticket);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ticket = $form->getData();
            $ticketService->create($ticket);

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
        $this->denyAccessUnlessGranted('delete', $ticket);

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

    /**
     * @Route("/ticket/{id}/assign-to", name="assign_ticket_to")
     */
    public function assignTo(Request $request, TicketService $ticketService, Ticket $ticket) {
        $this->denyAccessUnlessGranted('assignTo', $ticket);

        $form = $this->createForm(AssignToType::class, []);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->get('users')->getData();

            $ticketService->assignTo($ticket, $user);

            return $this->redirectToRoute('ticket');
        }

        return $this->render('ticket/assign-to.html.twig', [
            'form' => $form->createView(),
            'ticket' => $ticket
        ]);
    }
}
