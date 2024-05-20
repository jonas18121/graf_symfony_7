<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Requirement\Requirement;

class RecipeController extends AbstractController
{
    #[Route('/admin/recettes', name: 'app_admin_recipe_index')]
    public function index(
        Request $request, 
        RecipeRepository $recipeRepository
    ): Response
    {
        $duration = 90;
        $limit = 10;

        return $this->render('admin/recipe/index.html.twig', [
            'recipes' => $recipeRepository->findWithDurationLowerThan($duration, $limit),
        ]);
    }

    #[Route('/admin/recettes/create', name: 'app_admin_recipe_create')]
    public function create(
        Request $request, 
        EntityManagerInterface $entityManager,
    ): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $recipe->setCreatedAt(new DateTimeImmutable())
                ->setUpdatedAt(new DateTimeImmutable())
            ;

            $entityManager->persist($recipe);
            $entityManager->flush();

            $this->addFlash('success', 'La recette a bien été créée');
            return $this->redirectToRoute('app_admin_recipe_index');
        }

        return $this->render('admin/recipe/create.html.twig', [
            'form' => $form
        ]);
    }


    #[Route('/admin/recettes/{id}/edit', name: 'app_admin_recipe_edit', methods: ['GET', 'POST'], requirements: [ 'id' => Requirement::DIGITS ])]
    public function edit(
        Request $request, 
        EntityManagerInterface $entityManager,
        Recipe $recipe
    ): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $recipe->setUpdatedAt(new DateTimeImmutable());

            $entityManager->flush();

            $this->addFlash('success', 'La recette a bien été modifié');
            return $this->redirectToRoute('app_admin_recipe_index');
        }

        return $this->render('admin/recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form
        ]);
    }

    #[Route('/admin/recettes/{id}/delete', name: 'app_admin_recipe_delete', methods: ['DELETE'], requirements: [ 'id' => Requirement::DIGITS ])]
    public function delete(
        EntityManagerInterface $entityManager,
        Recipe $recipe
    ): Response
    {
        $entityManager->remove($recipe);
        $entityManager->flush();

        $this->addFlash('success', 'La recette a bien été supprimée');
        return $this->redirectToRoute('app_admin_recipe_index');
    }
}
