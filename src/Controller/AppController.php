<?php

namespace App\Controller;

use App\Entity\Artist;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AppController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage(Request $request)
    {
        return $this->render('front/homepage.html.twig');
    }

    /**
     * @Route("/festival", name="festival")
     */
    public function festival()
    {
        $em = $this->getDoctrine()->getManager();

        $artists = $em->getRepository(Artist::class)->findAllArtistsByPriority();

        return $this->render("front/festival.html.twig", array(
            "artists" => $artists
        ));
    }

    /**
     * @Route("/autres-evenements", name="events")
     */
    public function events()
    {
        return $this->render("front/events.html.twig");
    }

    /**
     * @Route("/galerie", name="gallery")
     */
    public function gallery()
    {
        return $this->render("front/gallery.html.twig");
    }
}
