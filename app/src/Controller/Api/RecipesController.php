<?php

namespace App\Controller\Api;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class RecipesController extends AbstractController
{
    #[Route("/api/recipes")]
    public function index(RecipeRepository $recipeRepository, Request $request)
    {
        $recipes = $recipeRepository->paginateRecipes($request->query->getInt('page', 1));
        return $this->json($recipes, 200, [], [
            'groups' => ['recipes:index']
        ]);
    }

    #[Route("/api/recipes/{id}")]
    public function show(Recipe $recipe)
    {
        return $this->json($recipe, 200, [], [
            'groups' => ['recipes:index', 'recipes:show']
        ]);
    }
}