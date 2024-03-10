<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeController extends AbstractController
{
    #[Route('/recette', name: 'app_recipe_index')]
    public function index(Request $request): Response
    {
        return $this->render('recipe/index.html.twig', [
            'controller_name' => 'RecipeController',
        ]);
    }

    #[Route('/recette/{slug}/{id}', name: 'app_recipe_show', requirements: [ 'id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(Request $request, string $slug, int $id): Response
    {

        dump($request->attributes->get("slug"), $request->attributes->get("id"));

        return $this->render('recipe/show.html.twig', [
            'slug' => $slug,
            'id' => $id,
        ]);
    }
}
