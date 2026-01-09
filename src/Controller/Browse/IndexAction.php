<?php

namespace App\Controller\Browse;

use App\Checkout\CheckoutManager;
use App\Repository\BookRepositoryInterface;
use App\Repository\CategoryRepositoryInterface;
use App\Repository\PaginationQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class IndexAction extends AbstractController {

    #[Route('/browse', name: 'browse')]
    public function __invoke(
        BookRepositoryInterface $bookRepository,
        CategoryRepositoryInterface $categoryRepository,
        CheckoutManager $checkoutManager,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $limit = 25,
        #[MapQueryParameter(filter: FILTER_DEFAULT, flags: FILTER_FLAG_EMPTY_STRING_NULL | FILTER_NULL_ON_FAILURE)] string|null $query = null,
        #[MapQueryParameter(name: 'category', filter: FILTER_DEFAULT, flags: FILTER_FLAG_EMPTY_STRING_NULL | FILTER_NULL_ON_FAILURE)] string|null $categoryId = null,
    ) {
        $categories = $categoryRepository->findAll();
        $category = null;

        if(!empty($categoryId)) {
            $category = $categoryRepository->findOneByAbbreviation($categoryId);
        }

        $books = $bookRepository->find(new PaginationQuery(page: $page, limit: $limit), $query, category: $category, onlyListed: true);

        $checkoutStatus = [ ];

        foreach($books as $book) {
            $checkoutStatus[$book->getId()] = $checkoutManager->getStatus($book);
        }

        return $this->render('browse/index.html.twig', [
            'books' => $books,
            'categories' => $categories,
            'category' => $category,
            'query' => $query,
            'limit' => $limit,
            'checkoutStatus' => $checkoutStatus
        ]);
    }
}
