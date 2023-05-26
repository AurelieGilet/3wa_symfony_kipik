<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Wallet;
use App\Form\RegistrationFormType;
use App\Form\ClientRegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
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
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher
    ): Response
    {
        $client = new Client();
        $wallet = new Wallet();

        $form = $this->createForm(RegistrationFormType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wallet->setAmount(100);

            $this->em->persist($wallet);

            // encode the plain password
            $client->setPassword(
                $userPasswordHasher->hashPassword(
                    $client,
                    $form->get('plainPassword')->getData()
                )
            );

            $client->setRoles(['ROLE_USER']);
            $client->setCreatedAt(new \DateTimeImmutable());
            $client->setWallet($wallet);
            $client->setIsFullyRegistered(false);

            $this->em->persist($client);
            $this->em->flush();

            $this->addFlash('success', "Votre compte a bien été créé");

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/utilisateur/enregistrement', name: 'app_client_register')]
    public function clientRegister(Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->getUser()->getRoles()[0] === 'ROLE_ADMIN') {
            return $this->redirectToRoute('app_home');
        }

        $client = $this->getUser();

        $form = $this->createForm(ClientRegistrationFormType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client->setIsFullyRegistered(true);

            $this->em->persist($client);

            $this->em->flush();

            $this->addFlash('success', "Votre inscription a bien été finalisée");

            return $this->redirectToRoute('app_user_account');
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
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

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
