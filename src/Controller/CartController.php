<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends AbstractController
{
    #[Route('/panier', name: 'app_cart')]
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        $cart = $session->get('cart', []);

        $cartWidthData = [];

        foreach ($cart as $id => $quantity) {
            $cartWidthData[] = [
                'product' => $productRepository->find($id),
                'quantity' => $quantity,
            ];
        }

        $total = 0;

        foreach ($cartWidthData as $item) {
            $totalItem = $item['product']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cartWidthData,
            'total' => $total,
        ]);
    }

    #[Route('/panier/ajouter/{id}', name: 'app_cart_add')]
    public function addToCart(Product $product, SessionInterface $session, UserRepository $userRepository)
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

        // Create/get cart and add products
        $cart = $session->get('cart', []);

        if (!empty($cart[$product->getId()])) {
            $cart[$product->getId()]++;
        } else {
            $cart[$product->getId()] = 1;
        }

        $session->set('cart', $cart); 
        
        $this->addFlash('success', "Produit ajouté au panier !");

        return $this->redirectToRoute('app_product_detail', ['name' => $product->getName() ]);
    }
}
