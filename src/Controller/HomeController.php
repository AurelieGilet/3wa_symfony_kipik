<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    #[Route('/', name: 'app_home')]
    public function index(
        CategoryRepository $categoryRepository, 
        ProductRepository $productRepository
    ): Response
    {
        $categories = $categoryRepository->findAll();

        $products = $productRepository->findAll();
    
        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }

    #[Route('/categorie/{name}', name: 'app_filter_category')]
    public function filterProducts(
        Category $category,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository
    ): Response
    {
        $categories = $categoryRepository->findAll();

        $products = $productRepository->findByCategory($category);

        dump($category);

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'currentCategory' => $category,
            'products' => $products,
        ]);
    }
}
