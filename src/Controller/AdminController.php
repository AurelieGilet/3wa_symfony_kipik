<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductFormType;
use App\Form\CategoryFormType;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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

    #[Route('/admin/categories/ajouter', name: 'app_admin_category_add')]
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

    #[Route('/admin/categories/modifier/{id}', name: 'app_admin_category_edit')]
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

    #[Route('/admin/categories/supprimer/{id}', name: 'app_admin_category_delete')]
    public function adminCategoryDelete(Category $category): Response
    {
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

    #[Route('/admin/produits/ajouter', name: 'app_admin_product_add')]
    public function adminProductAdd(Request $request): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();

            if ($file) {
                $productName = $form->getData()->getName();
                $fileName = preg_replace('/\s+/', '_', $productName) . '.' . $file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('products_images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', "Un problème est survenu avec l'upload de l'image");

                    return $this->redirectToRoute('app_admin_product_add');                
                }

                $product->setImage($fileName);
            }

            $this->em->persist($product);
            $this->em->flush();

            $this->addFlash('success', "Le produit a bien été ajouté");

            return $this->redirectToRoute('app_admin_products');
        }

        return $this->render('admin/admin_product_form.html.twig', [
            'form' => $form,
            'action' => 'Ajouter',
        ]);
    }

    #[Route('/admin/produits/modifier/{id}', name: 'app_admin_product_edit')]
    public function adminProductEdit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productName = $form->getData()->getName();

            $oldFile = $product->getImage();

            $file = $form->get('image')->getData();

            if ($file) {
                $filesystem = new Filesystem();
                $filesystem->remove($this->getParameter('products_images_directory') . '/' . $oldFile);

                $fileName = preg_replace('/\s+/', '_', strtolower($productName)) . '.' . $file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('products_images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', "Un problème est survenu avec l'upload de l'image");

                    return $this->redirectToRoute('app_admin_product_add');                
                }

                $product->setImage($fileName);
            } else {
                $pathParts = pathinfo($oldFile);
                $fileExtension = $pathParts['extension'];
                $newName = preg_replace('/\s+/', '_', strtolower($productName)) . '.' . $fileExtension;

                rename(
                    $this->getParameter('products_images_directory') . '/' .$oldFile, 
                    $this->getParameter('products_images_directory') . '/' .$newName
                );
                
                $product->setImage($newName);
            }
            
            $this->em->persist($product);
            $this->em->flush();

            $this->addFlash('success', "Le produit a bien été modifié");

            return $this->redirectToRoute('app_admin_products');
        }

        return $this->render('admin/admin_product_form.html.twig', [
            'form' => $form,
            'action' => 'Modifier',
        ]);
    }

    #[Route('/admin/produits/supprimer/{id}', name: 'app_admin_product_delete')]
    public function adminProductDelete(Product $product): Response
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->getParameter('products_images_directory') . '/' . $product->getImage());
        $this->em->remove($product);

        $this->em->flush();

        $this->addFlash('success', "Le produit a bien été supprimé");

        return $this->redirectToRoute('app_admin_products');
    }
}
