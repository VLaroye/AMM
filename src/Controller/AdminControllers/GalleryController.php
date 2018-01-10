<?php

namespace App\Controller\AdminControllers;

use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/galerie")
 */
class GalleryController extends Controller
{
    private $em;
    private $imageRepository;

    public function __construct(EntityManagerInterface $em, ImageRepository $imageRepository)
    {
        $this->em = $em;
        $this->imageRepository = $imageRepository;
    }

    /**
     * @Route("/", name="admin_gallery_index")
     */
    public function galleryIndex()
    {
        $images = $this->imageRepository->findBy(['inGallery' => true]);

        return $this->render('admin/gallery/admin_gallery_index.html.twig', [
            'images' => $images
        ]);
    }
}