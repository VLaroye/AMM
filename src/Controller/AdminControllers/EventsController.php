<?php

namespace App\Controller\AdminControllers;

use App\Entity\Event;
use App\Entity\EventCategory;
use App\Form\EventType;
use App\Form\EventCategoryType;
use App\Service\ImageUploader;
use App\Exception\PaginationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;

/**
 * @Route("/admin/evenements")
 */
class EventsController extends Controller
{
    private $em;
    private $imageUploader;
    private $fileSystem;

    const ITEM_PER_PAGE = 3;

    public function __construct(
        EntityManagerInterface $em,
        ImageUploader $imageUploader,
        Filesystem $fileSystem
    )
    {
        $this->em = $em;
        $this->imageUploader = $imageUploader;
        $this->fileSystem = $fileSystem;
    }

    /**
     * @param $page
     *
     * @return Response
     *
     * @throws PaginationException
     *
     * @Route("/{page}", requirements={"page" = "\d+"}, defaults={"page" = 1}, name="admin_events_index")
     */
    public function eventsIndex($page): Response
    {
        $eventRepository = $this->em->getRepository(Event::class);
        $eventCategoryRepository = $this->em->getRepository(EventCategory::class);
        $events = $eventRepository->findAllByBeginningDateTime($page, self::ITEM_PER_PAGE);
        $categories = $eventCategoryRepository->findAll();

        $pagination = [
            'page' => $page,
            'route' => 'admin_events_index',
            'pages_count' => max(ceil($events->count() / self::ITEM_PER_PAGE), 1),
            'route_params' => [],
        ];

        if ($page < 1 || $page > $pagination['pages_count']) {
            throw new PaginationException();
        }

        return $this->render('admin/events/admin_events_index.html.twig', [
            'events' => $events,
            'categories' => $categories,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route ("/add", name="admin_events_add")
     */
    public function addEvent(Request $request): Response
    {
        $event = new Event();

        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventCoverImageFileName = $this->imageUploader->upload($event->getCoverImage()->getFile());
            $eventImageFileName = $this->imageUploader->upload($event->getImage()->getFile());

            $event->getCoverImage()->setFileName($eventCoverImageFileName);
            $event->getImage()->setFileName($eventImageFileName);

            $this->em->persist($event);

            $this->em->flush();

            $this->addFlash('success', 'L\'évènement a bien été ajouté !');

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
    public function deleteEvent(Event $event): Response
    {
        $this->em->remove($event);
        $this->em->flush();

        $this->addFlash('success', 'L\'évènement a bien été supprimé !');

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
    public function updateEvent(Event $event, Request $request): Response
    {
        $form = $this->createForm(EventType::class, $event);

        $originalImage = $event->getImage();
        $originalCoverImage = $event->getCoverImage();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /* When form is submitted, check if user submitted a new image/coverImage or not. If he did, upload that new image and set fileName. If not, just reset original fileName */
            if (!$event->getImage()->getFile()) {
                $event->getImage()->setFileName($originalImage->getFileName());
            } else {
                $this->fileSystem->remove($this->getParameter('images_directory').'/'.$originalImage->getFileName());
                $newImage = $this->imageUploader->upload($event->getImage()->getFile());
                $event->getImage()->setFileName($newImage);
            }

            if (!$event->getCoverImage()->getFile()) {
                $event->getCoverImage()->setFileName($originalCoverImage->getFileName());
            } else {
                $this->fileSystem->remove($this->getParameter('images_directory').'/'.$originalCoverImage->getFileName());
                $newCoverImage = $this->imageUploader->upload($event->getCoverImage()->getFile());
                $event->getCoverImage()->setFileName($newCoverImage);
            }

            $this->em->flush();

            $this->addFlash('success', 'L\'évènement a bien été modifié !');

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
        $eventRepository = $this->em->getRepository(Event::class);
        $events = $eventRepository->findEventsByCategory($category->getId());

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
