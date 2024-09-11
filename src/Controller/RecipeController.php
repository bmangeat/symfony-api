<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\DateImmutableType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class RecipeController extends AbstractController
{
    #[Route('/recette', name: 'recipe.index')]
    public function index(Request $request, RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findBy([], ['duration' => 'ASC'], 10);
    
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }


    #[Route('/recette/{slug}/{id}', name: 'recipe.show', requirements: ['id' => '\d+']), ]
    public function show(Request $request, string $slug, int $id, RecipeRepository $recipeRepository): Response
    {
        $recipe = $recipeRepository->find($id);
        if ($recipe->getSlug() !== $slug) {
            return $this->redirectToRoute('recipe.show', [
                'id' => $recipe->getId(),
                'slug' => $recipe->getSlug()
            ]);
        };
        return $this->render('recipe/show.html.twig',
        [
            "id" => $id,
            "slug" => $slug,
            "recipe" => $recipe
        ]);
    }

    #[Route('/recette/{id}/edit', name: ('recipe.edit'))]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em) : Response 
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'La recette a bien été modifiée.');
            return $this->redirectToRoute('recipe.index');
        }
        return $this->render('recipe/edit.html.twig',
            [
                'recipe' => $recipe,
                'form' => $form
            ]);
    }

    #[Route('recette/create', name:'recipe.create', methods:['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em) : Response {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'La recette a bien été créée.');
            return $this->redirectToRoute('recipe.index');
        };

        return $this->render('recipe/create.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('recipe/delete/{id}', name: 'recipe.delete', methods:['DELETE'])]
    public function delete(Recipe $recipe, EntityManagerInterface $em): Response
    {
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('success', 'La recette a bien été supprimée.');

        return $this->redirectToRoute('recipe.index');
    }
}
