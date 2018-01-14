<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Event;
use App\Entity\Slider;
use App\Entity\SliderImage;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
            'sliderImages' => $sliderImages
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
     * @return Response
     *
     * @Route("/autres-evenements", name="events")
     */
    public function events(): Response
    {
        $em = $this->getDoctrine()->getManager();

        $events = $em->getRepository(Event::class)->findAll();

        return $this->render('front/events.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @return Response
     *
     * @Route("/galerie", name="gallery")
     */
    public function gallery(): Response
    {
        return $this->render('front/gallery.html.twig');
    }

    /**
     * @return Response
     *
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $transport = (new \Swift_SmtpTransport('in-v3.mailjet.com', 587))
                ->setUsername('dd21c8b6e1e4e358c5657bde0395f61b')
                ->setPassword('1b08163b62363b63dd5067efe28903c1');

            $mailer = new \Swift_Mailer($transport);

            $message = new \Swift_Message('Nouveau message du formulaire de contact !');
            $message
                ->setFrom('laroye.vincent@gmail.com')
                ->setTo('laroye.vincent@gmail.com')
                ->setBody($this->renderView(
                    'emails/contact.html.twig',
                    ['data' => $data]
                ), 'text/html');

            $mailer->send($message);

            $this->addFlash('success', 'Votre message a bien été envoyé ! Nous y répondrons au plus vite !');

            return $this->redirectToRoute('contact');
        }

        return $this->render('front/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
