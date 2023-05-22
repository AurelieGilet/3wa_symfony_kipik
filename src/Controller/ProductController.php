<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/product/{name}', name: 'app_product_detail')]
    public function index(Product $product): Response
    {
        return $this->render('product/product_detail.html.twig', [
            'product' => $product
        ]);
    }
}
