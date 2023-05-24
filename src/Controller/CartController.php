<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\UserRepository;
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
    public function addToCart(Product $product, CartService $cartService, UserRepository $userRepository)
    {
        // Make sure user is authenticated
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('error', "Vous devez vous inscrire afin de passer commande");

            return $this->redirectToRoute('app_register');
        }

        // Make sure user is a client
        $userType = $userRepository->getUserType($this->getUser());

        if ($userType[0]['type'] !== 'client') {
            $this->addFlash('error', "Vous devez finaliser votre inscription pour pouvoir commander");

            return $this->redirectToRoute('app_client_register');
        }

        // Add product to cart
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
