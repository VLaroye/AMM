<?php

namespace App\Controller\AdminControllers;

use App\Entity\Image;
use App\Form\ArtistType;
use App\Service\ImageUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Artist;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;

/**
 * Class ArtistsController
 * @package App\Controller\AdminControllers
 *
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/", name="admin_artists_index")
     */
    public function artistsIndex()
    {
        $artists = $this->artistRepository->findAllArtistsByPriority();

        return $this->render("admin/artists/admin_artists.html.twig", array(
            "artists" => $artists
        ));
    }

    /**
     * @param Request $request
     * @param ImageUploader $imageUploader
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/add", name="admin_artists_add")
     */
    public function artistsAdd(Request $request, ImageUploader $imageUploader)
    {
        $artist = new Artist();

        $form = $this->createForm(ArtistType::class, $artist);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = new Image();

            $uploadedImage = $imageUploader->upload($artist->getImageFile());

            $image->setFilename($uploadedImage);
            $image->setAlt($artist->getImageAlt());

            $artist->setImage($image);

            $this->em->persist($artist);

            $this->em->flush();

            return $this->redirectToRoute("admin_artists_index");
        }

        return $this->render('admin/artists/admin_artists_add.html.twig', array(
           'form' => $form->createView(),
        ));
    }

    /**
     * @param Artist $artist
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/delete/{id}", name="admin_artists_delete")
     */
    public function artistsDelete(Artist $artist)
    {
        // TODO : Supprimer l'image du dossier /images/uploads ?

        $this->em->remove($artist);
        $this->em->flush();

        return $this->redirectToRoute("admin_artists_index");
    }

    /**
     * @param Artist $artist
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/update/{id}", name="admin_artists_update")
     */
    public function artistsUpdate(Artist $artist, Request $request)
    {
        $form = $this->createForm(ArtistType::class, $artist);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $artist = $form->getData();

            $this->em->flush();

            return $this->redirectToRoute("admin_artists_index");
        }

        return $this->render("admin/artists/admin_artists_add.html.twig", array(
            'form' => $form->createView(),
        ));
    }
}
