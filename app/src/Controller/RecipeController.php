<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeController extends AbstractController
{
    #[Route('/recettes', name: 'app_recipe_index')]
    public function index(
        Request $request, 
        RecipeRepository $recipeRepository
    ): Response
    {
        $duration = 90;
        $limit = 10;

        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipeRepository->findWithDurationLowerThan($duration, $limit),
        ]);
    }

    #[Route('/recettes/{slug}/{id}', name: 'app_recipe_show', requirements: [ 'id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(
        Request $request, 
        string $slug, 
        int $id, 
        RecipeRepository $recipeRepository
    ): Response
    {
        dump($request->attributes->get("slug"), $request->attributes->get("id"));

        $recipe = $recipeRepository->find($id);

        // allows you to always have the right slug
        if ($recipe->getSlug() !== $slug) {
            return $this->redirectToRoute('app_recipe_show', ['slug' =>  $recipe->getSlug(), 'id' => $recipe->getId() ]);
        }

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/recettes/{id}/edit', name: 'app_recipe_edit', methods: ['GET', 'POST'])]
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
            return $this->redirectToRoute('app_recipe_index');
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form
        ]);
    }

    #[Route('/recettes/create', name: 'app_recipe_create')]
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
            return $this->redirectToRoute('app_recipe_index');
        }

        return $this->render('recipe/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/recettes/{id}/delete', name: 'app_recipe_delete', methods: ['DELETE'])]
    public function delete(
        EntityManagerInterface $entityManager,
        Recipe $recipe
    ): Response
    {
        $entityManager->remove($recipe);
        $entityManager->flush();

        $this->addFlash('success', 'La recette a bien été supprimée');
        return $this->redirectToRoute('app_recipe_index');
    }
}
