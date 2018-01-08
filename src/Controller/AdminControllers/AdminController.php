<?php

namespace App\Controller\AdminControllers;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @return Response
     *
     * @Route("/", name="admin_index")
     */
    public function adminIndex(): Response
    {
        return $this->render('admin/admin_homepage.html.twig');
    }
}
