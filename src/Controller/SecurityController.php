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
        $user = new User();
        $form = $this->createForm(LoginType::class, $user);
        $errorMessage = null;

        $error = $authUtils->getLastAuthenticationError();

        if (!is_null($error)) {
            $errorMessage = $error->getMessageKey();
        }

        return $this->render('admin/login.html.twig', [
            'form' => $form->createView(),
            'error' => $errorMessage,
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        // Route to log user out, defined in security.yaml.
        // Security component will take care of actually log the user out when he accesses that route. Nothing more to do here.
    }
}
