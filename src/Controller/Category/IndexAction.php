<?php

namespace App\Controller\Category;

use App\Repository\CategoryRepositoryInterface;
use App\Repository\PaginationQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class IndexAction extends AbstractController {

    #[Route('/categories', name: 'admin_categories')]
    public function __invoke(
        CategoryRepositoryInterface $categoryRepository,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter(filter: FILTER_DEFAULT, flags: FILTER_FLAG_EMPTY_STRING_NULL | FILTER_NULL_ON_FAILURE)] string|null $query = null
    ): Response {
        $categories = $categoryRepository->find(new PaginationQuery(page: $page), $query);

        return $this->render('categories/index.html.twig', [
            'categories' => $categories,
            'query' => $query
        ]);
    }
}
