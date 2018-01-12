<?php

namespace App\Controller\AdminControllers;

use App\Entity\Artist;
use App\Form\ArtistType;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;

/**
 * @Route("/admin/artistes")
 */
class ArtistsController extends Controller
{
    private $em;
    private $artistRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->artistRepository = $em->getRepository(Artist::class);
    }

    /**
     * @return Response
     *
     * @Route("/", name="admin_artists_index")
     */
    public function artistsIndex(): Response
    {
        $artists = $this->artistRepository->findAllArtistsByPriority();

        return $this->render('admin/artists/admin_artists.html.twig', [
            'artists' => $artists,
        ]);
    }

    /**
     * @param Request       $request
     * @param ImageUploader $imageUploader
     *
     * @return Response
     *
     * @Route("/add", name="admin_artists_add")
     */
    public function artistsAdd(Request $request, ImageUploader $imageUploader): Response
    {
        $artist = new Artist();

        $form = $this->createForm(ArtistType::class, $artist);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fileName = $imageUploader->upload($artist->getImage()->getFile());

            $artist->getImage()->setFileName($fileName);

            $this->em->persist($artist);

            $this->em->flush();

            return $this->redirectToRoute('admin_artists_index');
        }

        return $this->render('admin/artists/admin_artists_add.html.twig', [
           'form' => $form->createView(),
        ]);
    }

    /**
     * @param Artist $artist
     *
     * @return RedirectResponse
     *
     * @Route("/delete/{id}", name="admin_artists_delete")
     */
    public function artistsDelete(Artist $artist): RedirectResponse
    {
        // TODO : Supprimer l'image du dossier /images/uploads ?

        $this->em->remove($artist);
        $this->em->flush();

        return $this->redirectToRoute('admin_artists_index');
    }

    /**
     * @param Artist  $artist
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/update/{id}", name="admin_artists_update")
     */
    public function artistsUpdate(Artist $artist, Request $request): Response
    {
        $form = $this->createForm(ArtistType::class, $artist);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $artist = $form->getData();

            $this->em->flush();

            return $this->redirectToRoute('admin_artists_index');
        }

        return $this->render('admin/artists/admin_artists_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
