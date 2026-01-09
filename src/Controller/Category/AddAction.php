<?php

namespace App\Controller\Category;

use App\Book\Shelfmark\Generator;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepositoryInterface;
use App\Security\Voter\CategoryVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AddAction extends AbstractController {

    #[Route('/categories/add', name: 'admin_categories_add')]
    public function __invoke(
        CategoryRepositoryInterface $categoryRepository,
        Generator $generator,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(CategoryVoter::NEW);

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->persist($category);
            $this->addFlash('success', 'categories.add.success');

            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('admin/categories/add.html.twig', [
            'form' => $form->createView(),
            'strategies' => $generator->getStrategies()
        ]);
    }
}
