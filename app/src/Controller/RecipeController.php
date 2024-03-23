<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
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
        $duration = 20;
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
}
