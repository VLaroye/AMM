<?php

namespace App\Service;

use App\Entity\ContactMail;
use Swift_Message;

class ContactMailSender
{
    private $mailHost;
    private $mailPort;
    private $mailUsername;
    private $mailPassword;
    private $twig;

    public function __construct(
        $twig,
        $mailHost,
        $mailPort,
        $mailUsername,
        $mailPassword
        ) {
        $this->mailHost = $mailHost;
        $this->mailPort = $mailPort;
        $this->mailUsername = $mailUsername;
        $this->mailPassword = $mailPassword;
        $this->twig = $twig;
    }

    /**
     * @param ContactMail $mailData
     */
    public function sendMail(ContactMail $mailData)
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
            ]), 'text/html');

        $mailer->send($message);
    }
}
