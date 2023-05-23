<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Entity\Wallet;
use App\Form\RegistrationFormType;
use App\Form\ClientRegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setRoles(['ROLE_USER']);
            $user->setCreatedAt(new \DateTimeImmutable());

            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', "Votre compte a bien été créé");

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/utilisateur/enregistrement', name: 'app_client_register')]
    public function clientRegister(Request $request, Security $security): Response
    {
        $user = $this->getUser();
        $userEmail = $user->getEmail();
        $userPassword = $user->getPassword();
        $userCreatedAt = $user->GetCreatedAt();
        $client = new Client();
        $wallet = new Wallet();

        $form = $this->createForm(ClientRegistrationFormType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->remove($user);
            
            $this->em->flush();

            $wallet->setAmount(100);

            $this->em->persist($wallet);

            $client->setEmail($userEmail);
            $client->setPassword($userPassword);
            $client->setCreatedAt($userCreatedAt);
            $client->setRoles(['ROLE_USER']);
            $client->setWallet($wallet);
            $client->setIsFullyRegistered(true);

            $this->em->persist($client);

            $this->em->flush();

            $security->logout();

            $this->addFlash('success', "Votre inscription a bien été finalisée, veuillez vous identifier à nouveau");

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/client_register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param AuthenticationUtils $authenticationUtils
     * 
     * @return Response
     */
    #[Route('/connexion', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        if ($error) {
            $this->addFlash('error', "Mauvais identifiants");
        }

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/deconnexion', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
