<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @return RedirectResponse|Response
     */
    #[Route('/', name: 'app_main', methods: ['GET'])]
    public function index(): RedirectResponse|Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!isset($user)) {
           return $this->redirectToRoute('app_login');
        }

        return $this->render('dashboard/index.html.twig', [
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'createdAt' => $user->getCreatedAt(),
        ]);
    }
}
