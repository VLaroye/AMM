<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class FormAuthenticator extends AbstractFormLoginAuthenticator
{
    private $em;
    private $encoder;
    private $router;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, RouterInterface $router)
    {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->router = $router;
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request)
    {
        if ($request->getPathInfo() == "/login" && $request->isMethod("POST")) {
            return true;
        }
        return;
    }

    /**
     * @inheritDoc
     */
    public function getCredentials(Request $request)
    {
        return array(
            "username" => $request->request->get("username"),
            "password" => $request->request->get("password"),
        );
    }

    /**
     * @inheritDoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $this->em->getRepository(User::class)
            ->findOneBy(array("username" => $credentials["username"]));
    }

    /**
     * @inheritDoc
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        if ($this->encoder->isPasswordValid($user, $credentials['password'])) {
            return true;
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->router->generate('admin_index'));
    }

    /**
     * @inheritDoc
     */
    protected function getLoginUrl()
    {
        return $this->router->generate('login');
    }
}
