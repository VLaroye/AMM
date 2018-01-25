<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\ContactMail;
use App\Entity\Event;
use App\Entity\SliderImage;
use App\Form\ContactType;
use App\Service\FacebookApiRequest;
use App\Service\ContactMailSender;
use Cocur\Slugify\Slugify;
use Cocur\Slugify\SlugifyInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method as Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;

class AppController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/", name="homepage")
     */
    public function homepage(Request $request): Response
    {
        $sliderImages = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(SliderImage::class)
            ->getImagesByPosition();

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
        $em = $this->getDoctrine()->getManager();

        $artists = $em->getRepository(Artist::class)->findAllArtistsByPriority();

        return $this->render('front/festival.html.twig', [
            'artists' => $artists,
        ]);
    }

    /**
     * @Route("/artiste/{name}", name="artiste")
     */
    public function artist($name)
    {
        $em = $this->getDoctrine()->getManager();
        $artistsRepository = $em->getRepository(Artist::class);

        $artist = $artistsRepository->findOneBy([
            'slugifiedName' => $name
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
        $em = $this->getDoctrine()->getManager();

        $events = $em->getRepository(Event::class)->findAllFuturByBeginningDateTime();

        return $this->render('front/events.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @return Response
     *
     * @Route("/galerie", name="gallery")
     */
    public function albumGallery(FacebookApiRequest $facebookApiRequest): Response
    {
        $albumsData = $facebookApiRequest->facebookGetRequest('/167068823493165/albums/');
        $albums = $albumsData['data'];

        return $this->render('front/gallery.html.twig', [
            'albums' => $albums,
        ]);
    }

    /**
     * @Route("/galerie/{albumId}", name="gallery_by_album")
     */
    public function imagesGallery($albumId, FacebookApiRequest $facebookApiRequest, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $images = $facebookApiRequest->facebookGetRequest('/'.$albumId.'/photos?fields=images,name');
            $images = json_encode($images['data']);

            $response = new JsonResponse($images);

            return $response;
        }

        return new Response('Cette page n\'est pas accessible');
    }

    /**
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
     * @Route("/contact", name="post_contact")
     * @Method({"POST"})
     */
    public function postContact(Request $request, ContactMailSender $mailSender)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'Cette page n\'est accessible que depuis une requête Ajax.']);
        }

        $contactMail = new ContactMail();
        $form = $this->createForm(ContactType::class, $contactMail);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $mailSender->sendMail($contactMail);

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
