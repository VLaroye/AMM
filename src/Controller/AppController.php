<?php

namespace App\Controller;

use App\Entity\Artist;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AppController
 * @package App\Controller
 */
class AppController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/", name="homepage")
     */
    public function homepage(Request $request)
    {
        return $this->render('front/homepage.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/autres-evenements", name="events")
     */
    public function events()
    {
        return $this->render("front/events.html.twig");
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/galerie", name="gallery")
     */
    public function gallery()
    {
        return $this->render("front/gallery.html.twig");
    }
}
