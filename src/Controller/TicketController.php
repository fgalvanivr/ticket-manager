<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    /**
     * @Route("/ticket", name="ticket")
     */
    public function index()
    {
        return $this->render('ticket/index.html.twig', [
            'controller_name' => 'TicketController',
        ]);
    }

    public function show() {

    }

    public function edit() {

    }

    public function delete() {

    }
    
    public function open() {
        
    }

    public function reply() {

    }
    
    public function close() {

    }

    public function assign() {

    }
}
