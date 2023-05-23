<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserInterfaceController extends AbstractController
{
    #[Route('/utilisateur/compte', name: 'app_user_interface')]
    public function account(UserRepository $userRepository): Response
    {
        $userType = $userRepository->getUserType($this->getUser());

        return $this->render('user_interface/account.html.twig', [
            'userType' => $userType[0],
        ]);
    }

}
