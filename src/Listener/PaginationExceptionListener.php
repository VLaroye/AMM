<?php

namespace App\Listener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use App\Exception\PaginationException;

class PaginationExceptionListener
{
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (!$event->getException() instanceof PaginationException) {
            return;
        }

        $response = new Response();

        $response->setContent(
            $this->twig->render('admin/exceptions/pagination.incorrect_page.html.twig')
        );

        $response->setStatusCode(404);

        $event->setResponse($response);
    }
}
