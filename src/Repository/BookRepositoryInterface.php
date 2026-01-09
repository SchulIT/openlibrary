<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Category;

interface BookRepositoryInterface extends TransactionalRepositoryInterface {

    public function findOneByBarcodeId(string $barcodeId): ?Book;

    /**
     * @param PaginationQuery $paginationQuery
     * @param string|null $searchQuery
     * @param Category|null $category
     * @param bool $onlyListed
     * @return PaginatedResult<Book>
     */
    public function find(PaginationQuery $paginationQuery, ?string $searchQuery = null, ?Category $category = null, bool $onlyListed = false): PaginatedResult;

    /**
     * @return Book[]
     */
    public function findAll(): array;

    public function countAll(): int;

    public function persist(Book $book): void;

    public function remove(Book $book): void;
}
