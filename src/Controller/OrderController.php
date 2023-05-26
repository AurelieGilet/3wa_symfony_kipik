<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Address;
use App\Entity\OrderDetail;
use App\Service\Cart\CartService;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/panier/commander', name: 'app_order_cart')]
    public function orderCart(
        UserRepository $userRepository, 
        CartService $cartService, 
        ProductRepository $productRepository
    ): Response
    {
        // Make sure user is authenticated
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('error', "Vous devez vous inscrire afin de passer commande");

            return $this->redirectToRoute('app_register');
        }

        // Make sure user is a client
        $userType = $userRepository->getUserType($this->getUser());

        if ($userType[0]['type'] === 'admin') {
            $this->addFlash('error', "Un compte administrateur ne peux pas passer de commande");

            return $this->redirectToRoute('app_cart');
        }

        // Make sure client is fully setIsFullyRegistered
        if ($user->isIsFullyRegistered() === false) {
            $this->addFlash('error', "Vous devez finaliser votre inscription avant de passer de commande");

            return $this->redirectToRoute('app_client_register');
        }

        // Make sure client has enough credit to pay
        $total = $cartService->getCartTotalAmount();
        $wallet = $user->getWallet();

        if ($wallet->getAmount() < $total) {
            $this->addFlash(
                'error', 
                "Vous n'avez pas assez d'argent sur votre porte-monnaie pour commander, merci d'ajouter du crédit"
            );

            return $this->redirectToRoute('app_user_account');
        }

        $cartWithData = $cartService->getFullCart();
        $orderAmount = $cartService->getCartTotalAmount();

        // Create order address
        $orderAddress = new Address();

        $orderAddress->setStreet($user->getAddress()->getStreet());
        $orderAddress->setZip($user->getAddress()->getZip());
        $orderAddress->setCity($user->getAddress()->getCity());

        $this->em->persist($orderAddress);

        // Create order
        $order = new Order();

        $order->setClient($user);
        $order->setAddress($orderAddress);
        $order->setAmount($orderAmount);
        $order->setShippingName($user->getFirstname() . ' ' . $user->getLastName());
        $order->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($order);

        // Create order detail for each product
        foreach ($cartWithData as $item) {
            $orderDetail = new OrderDetail();

            $orderDetail->setOrderRef($order);
            $orderDetail->setProduct($item['product']);
            $orderDetail->setName($item['product']->getName());
            $orderDetail->setDescription($item['product']->getDescription());
            $orderDetail->setPrice($item['product']->getPrice());
            $orderDetail->setImage($item['product']->getImage());
            $orderDetail->setQuantity($item['quantity']);

            $this->em->persist($orderDetail);

            // Check product stock
            $product = $productRepository->find($item['product']);

            if ($product->getStock() < $item['quantity']) {
                $this->addFlash(
                    'error', 
                    "Le stock actuel du produit $product->getName() ne permet pas de passer commande. 
                    Veuillez modifier la quantité ($product->getStock() exemplaires restants)"
                );

                return $this->redirectToRoute('app_cart');
            } else {
                $product->setStock($product->getStock() - $item['quantity']);

                $this->em->persist($product);
            }
        }

        // Subtract order amount from user wallet
        $wallet->setAmount($wallet->getAmount() - $orderAmount);

        $this->em->persist($wallet);

        $this->em->flush();

        $cartService->emptyCart();

        $this->addFlash('success', "Votre commande a bien été enregistrée. Elle vous sera expédiée sous peu.");

        return $this->redirectToRoute('app_user_orders');
    }
}
