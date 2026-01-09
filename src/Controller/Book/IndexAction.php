<?php

namespace App\Controller\Book;

use App\Repository\BookRepositoryInterface;
use App\Repository\CategoryRepositoryInterface;
use App\Repository\PaginationQuery;
use App\Security\Voter\BookVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class IndexAction extends AbstractController {

    #[Route('/books', name: 'admin_books')]
    public function __invoke(
        BookRepositoryInterface $bookRepository,
        CategoryRepositoryInterface $categoryRepository,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $limit = 25,
        #[MapQueryParameter(filter: FILTER_DEFAULT, flags: FILTER_FLAG_EMPTY_STRING_NULL | FILTER_NULL_ON_FAILURE)] string|null $query = null,
        #[MapQueryParameter(name: 'category', filter: FILTER_DEFAULT, flags: FILTER_FLAG_EMPTY_STRING_NULL | FILTER_NULL_ON_FAILURE)] string|null $categoryId = null,
    ): Response {
        $this->denyAccessUnlessGranted(BookVoter::LIST);

        $books = $bookRepository->find(new PaginationQuery(page: $page, limit: $limit), $query);
        $categories = $categoryRepository->findAll();
        $category = null;

        if(!empty($categoryId)) {
            $category = $categoryRepository->findOneByAbbreviation($categoryId);
        }

        return $this->render('books/index.html.twig', [
            'books' => $books,
            'categories' => $categories,
            'category' => $category,
            'query' => $query,
            'limit' => $limit
        ]);
    }
}
