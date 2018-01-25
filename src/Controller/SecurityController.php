<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;

/**
 * Class SecurityController
 */
class SecurityController extends Controller
{
    /**
     * @param Request             $request
     * @param AuthenticationUtils $authUtils
     *
     * @return Response
     *
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authUtils): Response
    {
        $error = $authUtils->getLastAuthenticationError();
        $error = $error->getMessage();

        $lastUsername = $authUtils->getLastUsername();

        return $this->render('admin/login.html.twig', [
            'error' => $error,
            'lastUsername' => $lastUsername
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
    }
}
