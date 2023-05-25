<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\Cart\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/panier', name: 'app_cart')]
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cartService->getFullCart(),
            'total' => $cartService->getCartTotalAmount(),
        ]);
    }

    #[Route('/panier/ajouter/{id}', name: 'app_cart_add')]
    public function addToCart(Product $product, CartService $cartService)
    {
        if ($product->getStock() < 1) {
            $this->addFlash('error', "Ce produit n'a pas un stock suffisant pour être ajouté à votre panier");

            return $this->redirectToRoute('app_product_detail', ['name' => $product->getName() ]);
        }
        
        $cartService->addToCart($product->getId());
        
        $this->addFlash('success', "Produit ajouté au panier !");

        return $this->redirectToRoute('app_product_detail', ['name' => $product->getName() ]);
    }

    #[Route('/panier/supprimer/{id}', name: 'app_cart_remove')]
    public function removeFromCart($id, CartService $cartService)
    {
        $cartService->removeFromCart($id);

        $this->addFlash('success', "Produit supprimé du panier !");

        return $this->redirectToRoute('app_cart');
    }
}
