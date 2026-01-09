<?php

namespace App\Controller\Category;

use App\Book\Shelfmark\Generator;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepositoryInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EditAction extends AbstractController {

    #[Route('/categories/{uuid}/edit', name: 'admin_categories_edit')]
    public function __invoke(
        #[MapEntity(mapping: ['uuid' => 'uuid'])] Category $category,
        CategoryRepositoryInterface $categoryRepository,
        Generator $generator,
        Request $request
    ): Response {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->persist($category);
            $this->addFlash('success', 'categories.edit.success');

            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('admin/categories/edit.html.twig', [
            'form' => $form->createView(),
            'strategies' => $generator->getStrategies()
        ]);
    }
}
