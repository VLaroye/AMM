<?php

namespace App\Security;

use App\Entity\User;
use App\Form\LoginType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class FormAuthenticator extends AbstractFormLoginAuthenticator
{
    private $em;
    private $encoder;
    private $router;
    private $formFactory;
    private $twig;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, RouterInterface $router, FormFactoryInterface $formFactory, \Twig_Environment $twig, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->twig = $twig;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request)
    {
        $isLoginSubmit = $request->getPathInfo() == '/login' && $request->isMethod('POST');

        if (!$isLoginSubmit) {
            return;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        $csrfToken = $request->request->get('login');
        $csrfToken = $csrfToken['_token'];

        if (false === $this->csrfTokenManager->isTokenValid(new CsrfToken('authenticate', $csrfToken))) {
            throw new InvalidCsrfTokenException('Invalid CSRF token.');
        }

        $form = $this->formFactory->create(LoginType::class);

        $form->handleRequest($request);

        $data = $form->getData();

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials->getUsername();

        $user = $this->em->getRepository(User::class)
            ->findOneBy(['username' => $username]);

        if (is_null($user)) {
            throw  new CustomUserMessageAuthenticationException('Mauvais pseudo/Mot de passe');
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        if (!$this->encoder->isPasswordValid($user, $credentials->getPassword())) {
            throw  new CustomUserMessageAuthenticationException('Mauvais pseudo/Mot de passe');
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->router->generate('admin_index'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
    }

    /**
     * {@inheritdoc}
     */
    protected function getLoginUrl()
    {
        return $this->router->generate('login');
    }
}
