<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;

class AppController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/", name="homepage")
     */
    public function homepage(Request $request): Response
    {
        return $this->render('front/homepage.html.twig');
    }

    /**
     * @return Response
     *
     * @Route("/festival", name="festival")
     */
    public function festival(): Response
    {
        $em = $this->getDoctrine()->getManager();

        $artists = $em->getRepository(Artist::class)->findAllArtistsByPriority();

        return $this->render('front/festival.html.twig', [
            'artists' => $artists,
        ]);
    }

    /**
     * @return Response
     *
     * @Route("/autres-evenements", name="events")
     */
    public function events(): Response
    {
        $em = $this->getDoctrine()->getManager();

        $events = $em->getRepository(Event::class)->findAll();

        return $this->render('front/events.html.twig', [
            'events' => $events
        ]);
    }

    /**
     * @return Response
     *
     * @Route("/galerie", name="gallery")
     */
    public function gallery(): Response
    {
        return $this->render('front/gallery.html.twig');
    }
}
