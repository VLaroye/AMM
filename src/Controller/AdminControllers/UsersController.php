<?php

namespace App\Controller\AdminControllers;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @Route("/", name="admin_users_index")
     */
    public function usersIndex()
    {
        $users = $this->userRepository->findAll();

        return $this->render('admin/users/admin_users_index.html.twig', array(
            'users' => $users
        ));
    }

    /**
     * @Route("/add", name="admin_users_add")
     */
    public function userAdd(Request $request, UserPasswordEncoderInterface $encoder)
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

        return $this->render('admin/users/admin_users_add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/delete/{id}", name="admin_users_delete")
     */
    public function userDelete(User $user)
    {
        $this->em->remove($user);

        $this->em->flush();

        return $this->redirectToRoute('admin_users_index');
    }

    /**
     * @Route("/update/{id}", name="admin_users_update")
     */
    public function userUpdate()
    {

    }
}