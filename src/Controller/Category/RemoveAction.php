<?php

namespace App\Controller\Category;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Form\RemoveCategoryType;
use App\Repository\CategoryRepositoryInterface;
use App\Security\Voter\CategoryVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RemoveAction extends AbstractController {

    #[Route('//categories/{uuid}/remove', name: 'remove_category')]
    public function __invoke(
        #[MapEntity(mapping: ['uuid' => 'uuid'])] Category $category,
        CategoryRepositoryInterface $categoryRepository,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(CategoryVoter::DELETE, $category);

        $form = $this->createForm(RemoveCategoryType::class, [], [
            'message' => 'categories.remove.confirm',
            'message_parameters' => [
                'name' => $category->getName()
            ],
            'category' => $category
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->remove($category, $form->get('category')->getData());

            $this->addFlash('success', 'categories.remove.success');
            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('categories/remove.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }
}
