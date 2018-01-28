<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Model\ContactMail;
use App\Entity\Event;
use App\Entity\SliderImage;
use App\Form\ContactType;
use App\Service\FacebookApiRequest;
use App\Service\ContactMailSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method as Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;

class AppController extends Controller
{
    private $em;
    private $facebookApiRequest;
    private $contactMailSender;

    public function __construct(
        EntityManagerInterface $em,
        FacebookApiRequest $facebookApiRequest,
        ContactMailSender $contactMailSender
    )
    {
        $this->em = $em;
        $this->facebookApiRequest = $facebookApiRequest;
        $this->contactMailSender = $contactMailSender;
    }

    /**
     * @return Response
     *
     * @Route("/", name="homepage")
     */
    public function homepage(): Response
    {
        $sliderImageRepository = $this->em->getRepository(SliderImage::class);
        $sliderImages = $sliderImageRepository->getImagesByPosition();

        return $this->render('front/homepage.html.twig', [
            'sliderImages' => $sliderImages,
        ]);
    }

    /**
     * @return Response
     *
     * @Route("/festival", name="festival")
     */
    public function festival(): Response
    {
        $artistRepository = $this->em->getRepository(Artist::class);
        $artists = $artistRepository->findAllArtistsByPriority();

        return $this->render('front/festival.html.twig', [
            'artists' => $artists,
        ]);
    }

    /**
     * @param string $name
     *
     * @return Response
     *
     * @Route("/artiste/{name}", name="artiste")
     */
    public function artist(string $name): Response
    {
        $artistRepository = $this->em->getRepository(Artist::class);

        $artist = $artistRepository->findOneBy([
            'slugifiedName' => $name,
        ]);

        return $this->render('front/artist.html.twig', [
            'artist' => $artist,
        ]);
    }

    /**
     * @return Response
     *
     * @Route("/autres-evenements", name="events")
     */
    public function events(): Response
    {
        $eventRepository = $this->em->getRepository(Event::class);

        $events = $eventRepository->findAllFuturByBeginningDateTime();

        return $this->render('front/events.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @return Response
     *
     * @Route("/galerie", name="gallery")
     */
    public function albumGallery(): Response
    {
        $albumsData = $this->facebookApiRequest->facebookGetRequest('/167068823493165/albums/');
        $albums = $albumsData['data'];

        return $this->render('front/gallery.html.twig', [
            'albums' => $albums,
        ]);
    }

    /**
     * @param $albumId
     * @param Request            $request
     *
     * @return JsonResponse|Response
     *
     * @Route("/galerie/{albumId}", name="gallery_by_album")
     */
    public function imagesGallery($albumId, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $images = $this->facebookApiRequest->facebookGetRequest('/'.$albumId.'/photos?fields=images,name');
            $images = json_encode($images['data']);

            $response = new JsonResponse($images);

            return $response;
        }

        return new Response('Cette page n\'est pas accessible');
    }

    /**
     * @return Response
     *
     * @Route("/contact", name="contact")
     * @Method({"GET"})
     */
    public function getContact()
    {
        $contactMail = new ContactMail();
        $form = $this->createForm(ContactType::class, $contactMail, [
            'action' => $this->generateUrl('post_contact'),
        ]);

        return $this->render('front/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request           $request
     *
     * @return JsonResponse
     *
     * @Route("/contact", name="post_contact")
     * @Method({"POST"})
     */
    public function postContact(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'Cette page n\'est accessible que depuis une requête Ajax.']);
        }

        $contactMail = new ContactMail();
        $form = $this->createForm(ContactType::class, $contactMail);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->contactMailSender->sendMail($contactMail);

            return new JsonResponse(['message' => 'Mail envoyé !'], 200);
        }

        $formErrors = [];

        foreach ($form->getErrors(true) as $error) {
            $formErrors[$error->getCause()->getPropertyPath()] = $error->getMessage();
        }

        $response = new JsonResponse([
            'message' => 'Erreur lors de l\'envoi',
            'errors' => $formErrors,
        ], 400);

        return $response;
    }
}
