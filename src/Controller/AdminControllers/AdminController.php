<?php

namespace App\Controller\AdminControllers;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin_index")
     */
    public function adminIndex()
    {
        return $this->render("admin/admin_homepage.html.twig");
    }
}