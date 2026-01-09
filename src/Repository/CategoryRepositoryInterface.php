<?php

namespace App\Repository;

use App\Entity\Category;

interface CategoryRepositoryInterface {

    /**
     * @return Category[]
     */
    public function findAll(): array;

    /**
     * @param PaginationQuery $paginationQuery
     * @param string|null $query
     * @return PaginatedResult<Category>
     */
    public function find(PaginationQuery $paginationQuery, ?string $query = null): PaginatedResult;

    public function findByUuid(string $uuid): ?Category;

    public function findOneByAbbreviation(string $abbreviation): ?Category;

    public function persist(Category $category): void;

    public function remove(Category $category): void;
}
