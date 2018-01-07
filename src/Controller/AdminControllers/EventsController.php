<?php

namespace App\Controller\AdminControllers;

use App\Entity\Event;
use App\Entity\EventCategory;
use App\Form\EventCategoryType;
use App\Form\EventType;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;

// TODO : Inclure l'ajout et la suppression de catégories sur Index des evenements

/**
 * Class EventsController
 * @package App\Controller\AdminControllers
 *
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route ("/add", name="admin_events_add")
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
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/delete/{id}", name="admin_events_delete")
     */
    public function eventsDelete(Event $event)
    {
        $this->em->remove($event);
        $this->em->flush();

        return $this->redirectToRoute("admin_events_index");
    }

    /**
     * @param Event $event
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/update/{id}", name="admin_events_update")
     */
    public function eventsUpdate(Event $event, Request $request)
    {
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->flush();

            return $this->redirectToRoute("admin_events_index");
        }

        return $this->render("admin/events/admin_events_add.html.twig", array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/category/add", name="admin_eventsCategory_add")
     */
    public function eventsCategoryAdd(Request $request)
    {
        $category = new EventCategory();

        $form = $this->createForm(EventCategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // TODO : Ajouter FlashBag pour annoncer création de la catégorie
            $this->em->persist($category);

            $this->em->flush();

            $this->addFlash('info', 'La catégorie a été ajoutée !');

            return $this->redirectToRoute('admin_eventsCategory_add');
        }

        return $this->render('admin/events/admin_events_category.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param EventCategory $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws DBALException
     *
     * @Route("/category/delete/{id}", name="admin_eventsCategory_delete")
     */
    public function eventsCategoryDelete(EventCategory $category)
    {
        $events = $this->eventsRepository->findEventsByCategory($category->getId());

        if ($events != null) {
            // TODO : Rediriger vers les catégories avec flashbag "Il y a des evenenements ayant cette catégorie, les modifier avant de la supprimer"
            throw new DBALException("Impossible de supprimer cette catégorie");
        }

        $this->em->remove($category);
        $this->em->flush();

        return $this->redirectToRoute("admin_events_index");
    }
}
