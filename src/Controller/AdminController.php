<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileFormType;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\UuidV4;

/**
 * Class AdminController
 */
class AdminController extends AbstractController
{
    public UserService $userService;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    #[Route('/admin', name: 'app_admin', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pageSize = $request->query->getInt('size', 10);

        [$users, $totalItems, $pagesCount] = $this->userService->getUsers($page, $pageSize);

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $users,
            'pagination' => [
                'totalItems' => $totalItems,
                'pagesCount' => $pagesCount,
                'currentPage' => $page,
            ],
        ]);
    }

    /**
     * @param UuidV4                 $uuid
     * @param EntityManagerInterface $entityManager
     *
     * @return RedirectResponse|Response
     */
    #[Route('/admin/profile/{uuid}', name: 'app_view_profile', methods: ['GET'])]
    public function viewProfile(UuidV4 $uuid, EntityManagerInterface $entityManager): RedirectResponse|Response
    {
        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneBy(['uuid' => $uuid]);
        if (isset($user)) {
            return $this->render('profile/view.html.twig', [
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'createdAt' => $user->getCreatedAt(),
            ]);
        }

        return $this->redirectToRoute('app_admin');
    }

    /**
     * @param UuidV4                 $uuid
     * @param Request                $request
     * @param EntityManagerInterface $entityManager
     *
     * @return RedirectResponse|Response
     */
    #[Route('/admin/profile/{uuid}/edit', name: 'app_edit_profile', methods: ['GET', 'POST'])]
    public function editProfile(UuidV4 $uuid, Request $request, EntityManagerInterface $entityManager): RedirectResponse|Response
    {
        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneBy(['uuid' => $uuid]);
        if (isset($user)) {
            $form = $this->createForm(ProfileFormType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('app_admin');
            }

            return $this->render('profile/edit.html.twig', [
                'profileForm' => $form->createView(),
            ]);
        }

        return $this->redirectToRoute('app_admin');
    }
}
