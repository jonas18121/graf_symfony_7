<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Requirement\Requirement;

class CategoryController extends AbstractController
{
    #[Route('/admin/category', name: 'app_admin_category_index')]
    public function index(
        CategoryRepository $categoryRepository
    ): Response
    {
        return $this->render('admin/category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/admin/category/create', name: 'app_admin_category_create')]
    public function create(
        Request $request, 
        EntityManagerInterface $entityManager,
    ): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $category->setCreatedAt(new DateTimeImmutable())
                ->setUpdatedAt(new DateTimeImmutable())
            ;

            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'La categorie a bien été créée');
            return $this->redirectToRoute('app_admin_category_index');
        }

        return $this->render('admin/category/create.html.twig', [
            'form' => $form
        ]);
    }


    #[Route('/admin/category/{id}/edit', name: 'app_admin_category_edit', methods: ['GET', 'POST'], requirements: [ 'id' => Requirement::DIGITS ])]
    public function edit(
        Request $request, 
        EntityManagerInterface $entityManager,
        Category $category
    ): Response
    {
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $category->setUpdatedAt(new DateTimeImmutable());

            $entityManager->flush();

            $this->addFlash('success', 'La categorie a bien été modifié');
            return $this->redirectToRoute('app_admin_category_index');
        }

        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'form' => $form
        ]);
    }

    #[Route('/admin/category/{id}/delete', name: 'app_admin_category_delete', methods: ['DELETE'], requirements: [ 'id' => Requirement::DIGITS ])]
    public function delete(
        EntityManagerInterface $entityManager,
        Category $category
    ): Response
    {
        $entityManager->remove($category);
        $entityManager->flush();

        $this->addFlash('success', 'La categorie a bien été supprimée');
        return $this->redirectToRoute('app_admin_category_index');
    }
}
