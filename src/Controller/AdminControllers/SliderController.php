<?php

namespace App\Controller\AdminControllers;

use App\Entity\Slider;
use App\Entity\SliderImage;
use App\Form\SliderImageType;
use App\Form\UpdateSliderImageType;
use App\Repository\SliderImageRepository;
use App\Repository\SliderRepository;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\InvalidParameterException;

/**
 * @Route("/admin/slider")
 */
class SliderController extends Controller
{
    private $em;
    private $sliderRepository;
    private $sliderImageRepository;

    public function __construct(EntityManagerInterface $em, SliderRepository $sliderRepository, SliderImageRepository $sliderImageRepository)
    {
        $this->em = $em;
        $this->sliderRepository = $sliderRepository;
        $this->sliderImageRepository = $sliderImageRepository;
    }

    /**
     * @Route("/{id}/{page}", requirements={"page" = "\d+", "id" = "\d+"}, defaults={"page" = 1}, name="admin_slider_index")
     */
    public function manageSlider(Request $request, Slider $slider, $page = 1)
    {
        $sliderImages = $this->sliderImageRepository->getImagesByPosition($page, 10);

        $pagination = [
            'page' => $page,
            'route' => 'admin_slider_index',
            'pages_count' => ceil(count($sliderImages) / 10),
            'route_params' => ['id' => 1],
        ];

        if ($page > 1 && $page > $pagination['pages_count']) {
            throw new InvalidParameterException('Cette page n\'existe pas');
        }

        return $this->render('admin/slider/admin_slider_index.html.twig', [
            'slider' => $slider,
            'sliderImages' => $sliderImages,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @param int           $sliderId
     * @param Request       $request
     * @param ImageUploader $imageUploader
     *
     * @return Response
     *
     * @Route("/addImage/{sliderId}", name="admin_slider_image_add")
     */
    public function addSliderImage($sliderId, Request $request, ImageUploader $imageUploader): Response
    {
        $sliderImage = new SliderImage();

        $form = $this->createForm(SliderImageType::class, $sliderImage, [
            'action' => $this->generateUrl('admin_slider_image_add', ['sliderId' => $sliderId]),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Slider $slider * */
            $slider = $this->sliderRepository->find($sliderId);

            $imageFileName = $imageUploader->upload($sliderImage->getFile());
            $sliderImage->setFileName($imageFileName);

            $slider->addImage($sliderImage);

            $slider->updateImagesPositions();

            $this->em->persist($sliderImage);

            $this->em->flush();

            $this->addFlash('success', 'L\'image a bien été ajoutée au diaporama');

            return $this->redirectToRoute('admin_slider_index', [
                'id' => $slider->getId(),
            ]);
        }

        return $this->render('admin/slider/admin_slider_image_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Slider      $slider
     * @param SliderImage $sliderImage
     * @param Request     $request
     *
     * @Route("/updateImage/{sliderId}/{imageId}", name="admin_slider_image_update")
     *
     * @ParamConverter("slider", options={"mapping": {"sliderId": "id"}})
     * @ParamConverter("sliderImage", options={"mapping": {"imageId": "id"}})
     *
     * @return Response
     */
    public function updateSliderImage(Request $request, Slider $slider, SliderImage $sliderImage): Response
    {
        /** Determine possible choices for the image position */
        $images = $this->sliderImageRepository->getImagesByPosition();
        $positions = [];
        foreach ($images as $index => $image) {
            $positions[] = $image->getPosition();
        }

        $form = $this->createForm(UpdateSliderImageType::class, $sliderImage, [
            'positions' => $positions
        ]);

        $initialSliderImagePosition = $sliderImage->getPosition();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var SliderImage $image */
            $imageAtWantedPosition = $this->sliderImageRepository
                ->findOneBy([
                    'position' => $sliderImage->getPosition(),
                ]);

            $imageAtWantedPosition->setPosition($initialSliderImagePosition);

            $this->em->flush();

            $this->addFlash('success', 'L\'image a bien été modifiée !');

            return $this->redirectToRoute('admin_slider_index', [
                'id' => $slider->getId(),
            ]);
        }

        return $this->render('admin/slider/admin_slider_image_update.html.twig', [
            'form' => $form->createView(),
            'sliderId' => $slider->getId(),
            'imageId' => $sliderImage->getId(),
        ]);
    }

    /**
     * @param Slider      $slider
     * @param SliderImage $sliderImage
     *
     * @return Response
     *
     * @Route("/deleteImage/{sliderId}/{imageId}", name="admin_slider_image_delete")
     *
     * @ParamConverter("slider", options={"mapping": {"sliderId": "id"}})
     * @ParamConverter("sliderImage", options={"mapping": {"imageId": "id"}})
     */
    public function deleteSliderImage(Slider $slider, SliderImage $sliderImage): Response
    {
        // TODO : Supprimer le fichier image du serveur

        $slider->removeImage($sliderImage);

        $slider->updateImagesPositions();

        $this->em->remove($sliderImage);

        $this->em->flush();

        $this->addFlash('success', 'L\'image a bien été supprimée !');

        return $this->redirectToRoute('admin_slider_index', [
            'id' => $slider->getId(),
        ]);
    }
}
