<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(CategoryRepository $repository): Response
    {
        $all = $repository->findAllByName();

        return $this->render('category/index.html.twig', [
            'categories' => $all,
        ]);
    }

    #[Route('/category/{id<\d+>}', name: 'app_category_show')]
    public function show(CategoryRepository $repository, Category $category, PaginatorInterface $paginator, Request $request): Response
    {
        $ad = $category->getAdvertisements();
        $category = $paginator->paginate(
            $ad,
            $request->query->getInt('page', 1),
            20
        );
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }
}
