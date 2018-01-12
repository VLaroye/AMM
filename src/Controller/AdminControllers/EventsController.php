<?php

namespace App\Controller\AdminControllers;

use App\Entity\{Event, EventCategory};
use App\Form\{EventType, EventCategoryType};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{Request, Response};
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;

/**
 * @Route("/admin/evenements")
 */
class EventsController extends Controller
{
    private $em;
    private $eventsRepository;
    private $eventsCategoryRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->eventsRepository = $em->getRepository(Event::class);
        $this->eventsCategoryRepository = $em->getRepository(EventCategory::class);
    }

    /**
     * @return Response
     *
     * @Route("/", name="admin_events_index")
     */
    public function eventsIndex(): Response
    {
        $events = $this->eventsRepository->findAll();
        $category = $this->eventsCategoryRepository->findAll();

        return $this->render('admin/events/admin_events_index.html.twig', [
            'events' => $events,
            'category' => $category,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route ("/add", name="admin_events_add")
     */
    public function eventsAdd(Request $request): Response
    {
        $event = new Event();

        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = $form->getData();

            $this->em->persist($event);

            $this->em->flush();

            return $this->redirectToRoute('admin_events_index');
        }

        return $this->render('admin/events/admin_events_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Event $event
     *
     * @return Response
     *
     * @Route("/delete/{id}", name="admin_events_delete")
     */
    public function eventsDelete(Event $event): Response
    {
        $this->em->remove($event);
        $this->em->flush();

        return $this->redirectToRoute('admin_events_index');
    }

    /**
     * @param Event   $event
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/update/{id}", name="admin_events_update")
     */
    public function eventsUpdate(Event $event, Request $request): Response
    {
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('admin_events_index');
        }

        return $this->render('admin/events/admin_events_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/category/add", name="admin_eventsCategory_add")
     */
    public function eventsCategoryAdd(Request $request): Response
    {
        $category = new EventCategory();

        $form = $this->createForm(EventCategoryType::class, $category, [
            'action' => $this->generateUrl('admin_eventsCategory_add'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($category);

            $this->em->flush();

            $this->addFlash('success', 'La catégorie a été ajoutée !');

            return $this->redirectToRoute('admin_events_index');
        }

        return $this->render('admin/events/admin_events_category_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param EventCategory $category
     *
     * @return Response
     *
     * @Route("/category/delete/{id}", name="admin_eventsCategory_delete")
     */
    public function eventsCategoryDelete(EventCategory $category): Response
    {
        $events = $this->eventsRepository->findEventsByCategory($category->getId());

        if ($events != null) {
            $this->addFlash('warning', 'Impossible de supprimer une catégorie déjà associée à des évènements. Modifier la catégorie de ces évènements avant de supprimer la catégorie.');

            return $this->redirectToRoute('admin_events_index');
        }

        $this->addFlash('success', 'Catégorie supprimée !');

        $this->em->remove($category);
        $this->em->flush();

        return $this->redirectToRoute('admin_events_index');
    }
}

