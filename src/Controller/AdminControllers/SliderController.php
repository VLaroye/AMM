<?php

namespace App\Controller\AdminControllers;

use App\Entity\Slider;
use App\Entity\SliderImage;
use App\Form\SliderImageType;
use App\Repository\SliderRepository;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use function MongoDB\BSON\toJSON;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/slider")
 */
class SliderController extends Controller
{
    private $em;
    private $sliderRepository;

    public function __construct(EntityManagerInterface $em, SliderRepository $sliderRepository)
    {
        $this->em = $em;
        $this->sliderRepository = $sliderRepository;
    }

    /**
     * @Route("/{id}", name="admin_slider_index")
     */
    public function adminSliderManagement(Request $request, Slider $slider)
    {
        return $this->render("admin/slider/admin_slider_index.html.twig", [
            'slider' => $slider,
        ]);
    }

    /**
     * @param int $sliderId
     * @param Request $request
     * @param ImageUploader $imageUploader
     * @return Response
     *
     * @Route("/addImage/{sliderId}", name="admin_slider_image_add")
     */
    public function sliderImageAdd(int $sliderId, Request $request, ImageUploader $imageUploader): Response
    {
        $sliderImage = new SliderImage();

        $form = $this->createForm(SliderImageType::class, $sliderImage, [
            'action' => $this->generateUrl('admin_slider_image_add', ['sliderId' => $sliderId])
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slider = $this->sliderRepository->find($sliderId);

            $imageFileName = $imageUploader->upload($sliderImage->getFile());
            $sliderImage->setFileName($imageFileName);

            $position = $this->sliderRepository->getMaxImagePosition()[0]['position'] += 1;

            $sliderImage->setPosition($position);

            $slider->addImage($sliderImage);

            $this->em->persist($sliderImage);

            $this->em->flush();

            $this->addFlash('succes', 'L\'image a bien été ajoutée au diaporama');

            return $this->redirectToRoute('admin_slider_index');
        }

        return $this->render('admin/slider/admin_slider_image_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Slider $slider
     * @param SliderImage $sliderImage
     *
     * @return Response
     *
     * @Route("/deleteImage/{sliderId}/{imageId}", name="admin_slider_image_delete")
     * @ParamConverter("slider", options={"mapping": {"sliderId": "id"}})
     * @ParamConverter("sliderImage", options={"mapping": {"imageId": "id"}})
     */
    public function sliderImageDelete(Slider $slider, SliderImage $sliderImage): Response
    {
        // TODO : Supprimer le fichier image du serveur
        $slider->removeImage($sliderImage);

        $this->em->remove($sliderImage);

        $this->em->flush();

        return $this->redirectToRoute('admin_slider_index');
    }
}

