<?php

namespace App\Controller\AdminControllers;

use App\Entity\Slider;
use App\Entity\SliderImage;
use App\Exception\PaginationException;
use App\Form\SliderImageType;
use App\Form\UpdateSliderImageType;
use App\Repository\SliderImageRepository;
use App\Repository\SliderRepository;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/slider")
 */
class SliderController extends Controller
{
    private $em;
    private $imageUploader;

    const ITEM_PER_PAGE = 5;

    public function __construct(
        EntityManagerInterface $em,
        ImageUploader $imageUploader
    )
    {
        $this->em = $em;
        $this->imageUploader = $imageUploader;
    }

    /**
     * @param int $page
     *
     * @return Response
     *
     * @throws PaginationException
     *
     * @Route("/{page}", requirements={"page" = "\d+"}, defaults={"page" = 1}, name="admin_slider_index")
     */
    public function manageSlider(int $page = 1)
    {
        $sliderRepository = $this->em->getRepository(Slider::class);
        $sliderImageRepository = $this->em->getRepository(SliderImage::class);

        if (!$sliderRepository->find(1)) {
            $slider = new Slider();
            $this->em->persist($slider);
            $this->em->flush();
        } else {
            $slider = $sliderRepository->find(1);
        }

        $sliderImages = $sliderImageRepository->getImagesByPosition($page, self::ITEM_PER_PAGE);

        $pagination = [
            'page' => $page,
            'route' => 'admin_slider_index',
            'pages_count' => max(ceil($sliderImages->count() / self::ITEM_PER_PAGE), 1),
            'route_params' => ['id' => 1],
        ];

        if ($page < 1 || $page > $pagination['pages_count']) {
            throw new PaginationException();
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
     *
     * @return Response
     *
     * @Route("/addImage/{sliderId}", name="admin_slider_image_add")
     */
    public function addSliderImage($sliderId, Request $request): Response
    {
        $sliderRepository = $this->em->getRepository(Slider::class);

        $sliderImage = new SliderImage();

        $form = $this->createForm(SliderImageType::class, $sliderImage, [
            'action' => $this->generateUrl('admin_slider_image_add', ['sliderId' => $sliderId]),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Slider $slider * */
            $slider = $sliderRepository->find($sliderId);

            $imageFileName = $this->imageUploader->upload($sliderImage->getFile());
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
        $sliderImageRepository = $this->em->getRepository(SliderImage::class);

        /** Determine possible choices for the image position */
        $images = $sliderImageRepository->getImagesByPosition();
        $positions = [];
        foreach ($images as $index => $image) {
            $positions[] = $image->getPosition();
        }

        $form = $this->createForm(UpdateSliderImageType::class, $sliderImage, [
            'positions' => $positions,
        ]);

        $initialSliderImagePosition = $sliderImage->getPosition();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var SliderImage $image */
            $imageAtWantedPosition = $sliderImageRepository
                ->findOneBy([
                    'position' => $sliderImage->getPosition(),
                ]);

            $imageAtWantedPosition->setPosition($initialSliderImagePosition);

            $this->em->flush();

            $this->addFlash('success', 'L\'image a bien été modifiée !');

            return $this->redirectToRoute('admin_slider_index');
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
        $slider->removeImage($sliderImage);

        $slider->updateImagesPositions();

        $this->em->remove($sliderImage);

        $this->em->flush();

        $this->addFlash('success', 'L\'image a bien été supprimée !');

        return $this->redirectToRoute('admin_slider_index');
    }
}
