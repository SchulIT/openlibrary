<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Category;

interface BookRepositoryInterface extends TransactionalRepositoryInterface {

    public function findOneById(int $id): ?Book;

    public function findOneByBarcodeId(string $barcodeId): ?Book;

    /**
     * @param PaginationQuery $paginationQuery
     * @param OrderBy $orderBy
     * @param string|null $searchQuery
     * @param Category|null $category
     * @param bool $onlyListed
     * @return PaginatedResult<Book>
     */
    public function find(PaginationQuery $paginationQuery, OrderBy $orderBy, ?string $searchQuery = null, ?Category $category = null, bool $onlyListed = false): PaginatedResult;

    /**
     * @return Book[]
     */
    public function findAll(): array;

    public function countAll(): int;

    public function persist(Book $book): void;

    public function remove(Book $book): void;
}
