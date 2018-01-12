<?php

namespace App\Controller\AdminControllers;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;

/**
 * @Route("/admin/utilisateurs")
 */
class UsersController extends Controller
{
    private $em;
    private $userRepository;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->userRepository = $em->getRepository(User::class);
    }

    /**
     * @return Response
     *
     * @Route("/", name="admin_users_index")
     */
    public function usersIndex(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('admin/users/admin_users_index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $encoder
     *
     * @return Response
     *
     * @Route("/add", name="admin_users_add")
     */
    public function userAdd(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $encodedPassword = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($encodedPassword);

            $this->em->persist($user);

            $this->em->flush();

            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('admin/users/admin_users_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param User $user
     *
     * @return Response
     *
     * @Route("/delete/{id}", name="admin_users_delete")
     */
    public function userDelete(User $user): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $this->em->remove($user);

        $this->em->flush();

        return $this->redirectToRoute('admin_users_index');
    }
}
