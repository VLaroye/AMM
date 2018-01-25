<?php

namespace App\Controller\AdminControllers;

use App\Entity\Artist;
use App\Exception\PaginationException;
use App\Form\ArtistType;
use App\Service\ImageUploader;
use App\Service\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
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
     * @param int $page
     * @return Response
     * @throws PaginationException
     *
     * @Route("/{page}", requirements={"page" = "\d+"}, defaults={"page" = 1}, name="admin_artists_index")
     */
    public function artistsIndex($page = 1)
    {
        $artists = $this->artistRepository->findAllArtistsByPriority($page, 4);

        $pagination = [
            'page' => $page,
            'route' => 'admin_artists_index',
            'pages_count' => max(ceil(count($artists) / 4), 1),
            'route_params' => [],
        ];

        if ($page < 1 || $page > $pagination['pages_count']) {
            throw new PaginationException();
        }

        return $this->render('admin/artists/admin_artists.html.twig', [
            'artists' => $artists,
            'pagination' => $pagination,
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
    public function addArtist(Request $request, ImageUploader $imageUploader, Slugify $slugify): Response
    {
        $artist = new Artist();

        $form = $this->createForm(ArtistType::class, $artist);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFileName = $imageUploader->upload($artist->getImage()->getFile());

            $artist->getImage()->setFileName($imageFileName);

            $artist->setSlugifiedName($slugify->slugify($artist->getName()));
            $this->em->persist($artist);

            $this->em->flush();

            $this->addFlash('success', 'L\'artiste a bien été ajouté !');

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
    public function deleteArtist(Artist $artist): RedirectResponse
    {
        $this->em->remove($artist);
        $this->em->flush();

        $this->addFlash('success', 'L\'artiste a bien été supprimé !');

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
    public function updateArtist(Artist $artist, Request $request, ImageUploader $imageUploader, Filesystem $fs, Slugify $slugify): Response
    {
        $form = $this->createForm(ArtistType::class, $artist);

        $originalImage = $artist->getImage();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$artist->getImage()->getFile()) {
                $artist->getImage()->setFileName($originalImage->getFileName());
            } else {
                $fs->remove($this->getParameter('images_directory') . '/' . $originalImage->getFileName());
                $newImage = $imageUploader->upload($artist->getImage()->getFile());
                $artist->getImage()->setFileName($newImage);
            }

            $artist->setSlugifiedName($slugify->slugify($artist->getName()));
            $this->em->flush();

            $this->addFlash('success', 'L\'artiste a bien été modifié !');

            return $this->redirectToRoute('admin_artists_index');
        }

        return $this->render('admin/artists/admin_artists_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
