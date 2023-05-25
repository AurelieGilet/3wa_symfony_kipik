<?php

namespace App\Controller;

use App\Entity\Wallet;
use App\Form\WalletFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserInterfaceController extends AbstractController
{
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    #[Route('/utilisateur/compte', name: 'app_user_interface')]
    public function account(UserRepository $userRepository): Response
    {
        $userType = $userRepository->getUserType($this->getUser());

        $userType = $userType[0]['type'];

        return $this->render('user_interface/account.html.twig', [
            'userType' => $userType,
        ]);
    }

    #[Route('/utilisateur/portemonaie/crediter', name: 'app_wallet_credit')]
    public function addCredit(Request $request)
    {
        $wallet = $this->getUser()->getWallet();

        $form = $this->createForm(WalletFormType::class, $wallet, [
            'action' => $this->generateUrl('app_wallet_credit'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $amountToCredit = $form['amountToCredit']->getData();

                $wallet->setAmount($wallet->getAmount() + $amountToCredit);

                $this->em->persist($wallet);
                $this->em->flush();

                $this->addFlash('success', "Le portefeuille a bien été crédité");

                return $this->redirectToRoute('app_user_interface');
            }
        }

        return $this->render('user_interface/_wallet_form.html.twig', [
            'form' => $form,
        ]);
    }

}
