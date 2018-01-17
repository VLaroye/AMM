<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Entity\ContactMail;
use Swift_Message;
use Twig_Environment;

class ContactMailSender
{
    private $container;
    private $transport;
    private $mailer;
    private $message;
    private $twig;

    public function __construct(ContainerInterface $container, Twig_Environment $twig)
    {
        $this->container = $container;
        $this->twig = $twig;
        $this->transport = new \Swift_SmtpTransport();
        $this->setTransportParams();
        $this->mailer = new \Swift_Mailer($this->transport);
        $this->message = new Swift_Message();
    }

    public function setTransportParams(): void
    {
        $this->transport
            ->setHost($this->container->getParameter('mail.host'))
            ->setPort($this->container->getParameter('mail.port'))
            ->setUsername($this->container->getParameter('mail.username'))
            ->setPassword($this->container->getParameter('mail.password'));
    }

    /**
     * @param ContactMail $mailData
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendMail(ContactMail $mailData): void
    {
        $this->message
            ->setSubject($mailData->getSubject())
            ->setTo('laroye.vincent@gmail.com')
            ->setFrom('laroye.vincent@gmail.com')
            ->setBody($this->twig->render('emails/contact.html.twig', [
                'data' => $mailData,
            ]));

        $this->mailer->send($this->message);
    }
}
