<?php

namespace App\Controller\AdminControllers;

use App\Form\ArtistType;
use App\Form\ImageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Artist;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/artistes")
 */
class ArtistsController extends Controller
{
    private $em;
    private $artistRepository;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->artistRepository = $em->getRepository(Artist::class);
    }

    /**
     * @Route("/", name="admin_artists_index")
     */
    public function artistsIndex()
    {
        $artists = $this->artistRepository->findAll();

        return $this->render("admin/admin_artists.html.twig", array(
            "artists" => $artists
        ));
    }

    /**
     * @Route("/add", name="admin_artists_add")
     */
    public function artistsAdd(Request $request)
    {
        $artist = new Artist();

        $form = $this->createForm(ArtistType::class, $artist);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $artist = $form->getData();

            $this->em->persist($artist);

            $this->em->flush();
        }

        return $this->render('admin/admin_artists_add.html.twig', array(
           'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/delete/{id}", name="admin_artists_delete")
     */
    public function artistsDelete($id)
    {

    }

    /**
     * @Route("/update/{id]", name="admin_artists_update")
     */
    public function artistsUpdate()
    {

    }
}