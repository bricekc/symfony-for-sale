<?php

namespace App\Controller;

use App\Entity\User;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/{id}', name: 'app_user')]
    public function index(User $user, PaginatorInterface $paginator, Request $request): Response
    {
        $ads = $user->getAdvertisements();
        $ads = $paginator->paginate(
            $ads,
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('user/index.html.twig', [
            'advertisements' => $ads,
            'user_id' => $user->getId(),
        ]);
    }
}
