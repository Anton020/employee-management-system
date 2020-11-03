<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventsController extends AbstractController
{
    /**
     * @Route("/events", name="events")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Event::class);

        return $this->render('events/index.html.twig', [
            'events' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/events/add", name="events_add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request)
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            $this->addFlash('success', 'wydarzenie dodane');

            return $this->redirectToRoute('events');
        }

        return $this->render('events/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
