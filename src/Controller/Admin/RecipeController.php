<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/admin/recette', name: 'admin.recipe.')]
class RecipeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findBy([], ['duration' => 'ASC'], 10);
    
        return $this->render('admin/recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }

    #[Route('/create', name:'create', methods:['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em) : Response {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'La recette a bien été créée.');
            return $this->redirectToRoute('admin.recipe.index');
        };

        return $this->render('admin/recipe/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: ('edit'),  methods:['GET', 'POST'], requirements:['id' => Requirement::DIGITS])]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em) : Response 
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'La recette a bien été modifiée.');
            return $this->redirectToRoute('admin.recipe.index');
        }
        return $this->render('admin/recipe/edit.html.twig',
            [
                'recipe' => $recipe,
                'form' => $form
            ]);
    }

    #[Route('/{id}', name: 'delete', methods:['DELETE'], requirements:['id' => Requirement::DIGITS])]
    public function delete(Recipe $recipe, EntityManagerInterface $em): Response
    {
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('success', 'La recette a bien été supprimée.');

        return $this->redirectToRoute('admin.recipe.index');
    }
}
