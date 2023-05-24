<?php

namespace App\Service\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService {
    protected $session;
    protected $productRepository;

    public function __construct(RequestStack $requestStack, ProductRepository $productRepository)
    {
        $this->session = $requestStack->getSession();
        $this->productRepository = $productRepository;
    }

    public function addToCart(int $id) 
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart); 
    }

    public function removeFromCart(int $id)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$id]) && $cart[$id] > 1) {
            $cart[$id] -= 1;
        } elseif (!empty($cart[$id]) ) {
            unset($cart[$id]);
        }

        $this->session->set('cart', $cart);
    }

    public function getFullCart(): array  
    {
        $cart = $this->session->get('cart', []);

        $cartWithData = [];

        foreach ($cart as $id => $quantity) {
            $cartWithData[] = [
                'product' => $this->productRepository->find($id),
                'quantity' => $quantity,
            ];
        }

        return $cartWithData;
    }

    public function getCartTotalAmount(): float
    {
        $totalAmount = 0;

        foreach ($this->getFullCart() as $item) {
            $totalAmount += $item['product']->getPrice() * $item['quantity'];
        }

        return $totalAmount;
    }

    public function getCartTotalItems(): int
    {
        $totalItems = 0;

        foreach ($this->getFullCart() as $item) {
            $totalItems += $item['quantity'];
        }

        return $totalItems;
    }
}