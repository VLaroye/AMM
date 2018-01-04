<?php

namespace App\Controller\AdminControllers;

use App\Entity\Event;
use App\Form\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/evenements")
 */
class EventsController extends Controller
{
    private $em;
    private $eventsRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->eventsRepository = $em->getRepository(Event::class);
    }

    /**
     * @Route("/", name="admin_events_index")
     */
    public function eventsIndex()
    {
        $events = $this->eventsRepository->findAll();

        return $this->render("admin/events/admin_events_index.html.twig", array(
            "events" => $events
        ));
    }

    /**
     * @Route("/add", name="admin_events_add")
     */
    public function eventsAdd(Request $request)
    {
        $event = new Event();

        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = $form->getData();

            $this->em->persist($event);

            $this->em->flush();

            return $this->redirectToRoute("admin_events_index");
        }

        return $this->render('admin/events/admin_events_add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/delete/{id}", name="admin_events_delete")
     */
    public function artistsDelete(Event $event)
    {
        $this->em->remove($event);
        $this->em->flush();

        return $this->redirectToRoute("admin_events_index");
    }

    /**
     * @Route("/update/{id}", name="admin_events_update")
     */
    public function artistsUpdate(Event $event, Request $request)
    {
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = $form->getData();

            $this->em->flush();

            return $this->redirectToRoute("admin_events_index");
        }

        return $this->render("admin/events/admin_events_add.html.twig", array(
            'form' => $form->createView(),
        ));
    }
}
