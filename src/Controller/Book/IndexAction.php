<?php

namespace App\Controller\Book;

use App\Http\ValueResolver\MapOrderByQueryParameter;
use App\Repository\BookRepository;
use App\Repository\BookRepositoryInterface;
use App\Repository\CategoryRepositoryInterface;
use App\Repository\OrderBy;
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
        #[MapOrderByQueryParameter(allowedColumnNames: BookRepository::AllowedOrderByColumns, defaultColumnName: 'title')] OrderBy $orderBy,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $limit = 25,
        #[MapQueryParameter(filter: FILTER_DEFAULT, flags: FILTER_FLAG_EMPTY_STRING_NULL | FILTER_NULL_ON_FAILURE)] string|null $query = null,
        #[MapQueryParameter(name: 'category', filter: FILTER_DEFAULT, flags: FILTER_FLAG_EMPTY_STRING_NULL | FILTER_NULL_ON_FAILURE)] string|null $categoryId = null,
    ): Response {
        $this->denyAccessUnlessGranted(BookVoter::LIST);

        $categories = $categoryRepository->findAll();
        $category = null;

        if(!empty($categoryId)) {
            $category = $categoryRepository->findOneByAbbreviation($categoryId);
        }

        $books = $bookRepository->find(new PaginationQuery(page: $page, limit: $limit), $orderBy, $query, $category);

        return $this->render('books/index.html.twig', [
            'books' => $books,
            'categories' => $categories,
            'category' => $category,
            'query' => $query,
            'limit' => $limit
        ]);
    }
}
