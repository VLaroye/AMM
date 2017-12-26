<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage()
    {
        return $this->render('front/homepage.html.twig');
    }

    /**
     * @Route("/festival", name="festival")
     */
    public function festival()
    {
        return $this->render("front/festival.html.twig");
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
