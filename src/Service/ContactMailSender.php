<?php

namespace App\Service;

use App\Entity\ContactMail;
use Swift_Message;
use Twig_Environment;

class ContactMailSender
{
    private $mailHost;
    private $mailPort;
    private $mailUsername;
    private $mailPassword;
    private $twig;

    public function __construct(
        Twig_Environment $twig,
        $mailHost,
        $mailPort,
        $mailUsername,
        $mailPassword
        )
    {
        $this->mailHost = $mailHost;
        $this->mailPort = $mailPort;
        $this->mailUsername = $mailUsername;
        $this->mailPassword = $mailPassword;
        $this->twig = $twig;
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
        $transport = new \Swift_SmtpTransport();
        $transport
            ->setHost($this->mailHost)
            ->setPort($this->mailPort)
            ->setUsername($this->mailUsername)
            ->setPassword($this->mailPassword);

        $mailer = new \Swift_Mailer($transport);

        $message = new Swift_Message();

        $message
            ->setSubject($mailData->getSubject())
            ->setTo('laroye.vincent@gmail.com')
            ->setFrom('laroye.vincent@gmail.com')
            ->setBody($this->twig->render('emails/contact.html.twig', [
                'data' => $mailData,
            ]));

        $mailer->send($message);
    }
}
