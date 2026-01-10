<?php

namespace App\Repository;

use App\Entity\LabelTemplate;

interface LabelRepositoryInterface {

    /**
     * @return LabelTemplate[]
     */
    public function findAll(): array;

    /**
     * @param PaginationQuery $paginationQuery
     * @return PaginatedResult<LabelTemplate>
     */
    public function find(PaginationQuery $paginationQuery): PaginatedResult;

    public function persist(LabelTemplate $label): void;

    public function remove(LabelTemplate $label): void;
}
