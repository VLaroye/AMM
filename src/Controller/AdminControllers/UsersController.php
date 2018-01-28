<?php

namespace App\Controller\AdminControllers;

use App\Entity\User;
use App\Exception\PaginationException;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;

/**
 * @Route("/admin/utilisateurs")
 */
class UsersController extends Controller
{
    private $em;
    private $userPasswordEncoder;

    const ITEM_PER_PAGE = 10;

    public function __construct(
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $userPasswordEncoder
    )
    {
        $this->em = $em;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @param int $page
     *
     * @return Response
     *
     * @throws PaginationException
     *
     * @Route("/{page}", requirements={"page" = "\d+"}, defaults={"page" = 1}, name="admin_users_index")
     */
    public function usersIndex($page = 1)
    {
        $userRepository = $this->em->getRepository(User::class);
        $users = $userRepository->findAll($page, self::ITEM_PER_PAGE);

        $pagination = [
            'page' => $page,
            'route' => 'admin_artists_index',
            'pages_count' => max(ceil($users->count() / self::ITEM_PER_PAGE), 1),
            'route_params' => [],
        ];

        if ($page < 1 || $page > $pagination['pages_count']) {
            throw new PaginationException();
        }

        return $this->render('admin/users/admin_users_index.html.twig', [
            'users' => $users,
            'pagination' => $pagination,
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
    public function addUser(Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $encodedPassword = $this->userPasswordEncoder->encodePassword($user, $user->getPassword());

            $user->setPassword($encodedPassword);

            $this->em->persist($user);

            $this->em->flush();

            $this->addFlash('success', 'L\'utilisateur a bien été ajouté !');

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
    public function deleteUser(User $user): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $this->em->remove($user);

        $this->em->flush();

        $this->addFlash('success', 'L\'utilisateur a bien été supprimé !');

        return $this->redirectToRoute('admin_users_index');
    }
}
