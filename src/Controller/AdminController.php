<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    #[Route('/admin/utilisateurs', name: 'app_admin_users')]
    public function adminUsers(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/admin_users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/categories', name: 'app_admin_categories')]
    public function adminCategories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('admin/admin_categories.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/admin/categorie/ajouter', name: 'app_admin_category_add')]
    public function adminCategoryAdd(Request $request): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryFormType::class, $category, [
            'action' => $this->generateUrl('app_admin_category_add'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->em->persist($category);
                $this->em->flush();

                $this->addFlash('success', "La catégorie a bien été ajoutée");

                return $this->redirectToRoute('app_admin_categories');
            }
        }

        return $this->render('admin/_category_form.html.twig', [
            'form' => $form,
            'action' => 'Ajouter',
        ]);
    }

    #[Route('/admin/categorie/modifier/{id}', name: 'app_admin_category_edit')]
    public function adminCategoryEdit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryFormType::class, $category, [
            'action' => $this->generateUrl('app_admin_category_edit', ['id' => $category->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->em->persist($category);
                $this->em->flush();

                $this->addFlash('success', "La catégorie a bien été modifiée");

                return $this->redirectToRoute('app_admin_categories');
            }
        }

        return $this->render('admin/_category_form.html.twig', [
            'form' => $form,
            'action' => 'Modifier',
        ]);
    }

    #[Route('/admin/categorie/supprimer/{id}', name: 'app_admin_category_delete')]
    public function adminCategoryDelete(Request $request, Category $category): Response
    {
        dump($category->getProducts());
        if (count($category->getProducts()) > 0) {
            $this->addFlash('error', "Vous ne pouvez pas supprimer cette catégorie car elle est associée à 1 ou plusieurs produits");

            return $this->redirectToRoute('app_admin_categories');
        }

        $this->em->remove($category);

        $this->em->flush();

        $this->addFlash('success', "La catégorie a bien été supprimée");

        return $this->redirectToRoute('app_admin_categories');
    }

    #[Route('/admin/produits', name: 'app_admin_products')]
    public function adminProducts(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('admin/admin_products.html.twig', [
            'products' => $products,
        ]);
    }
}
