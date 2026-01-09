<?php

namespace App\Repository;

use App\Entity\Borrower;
use App\Entity\BorrowerType;

interface BorrowerRepositoryInterface extends TransactionalRepositoryInterface {

    public function findOneById(int $id): ?Borrower;

    public function findByBarcodeId(string $barcodeId): ?Borrower;

    /**
     * @param PaginationQuery $paginationQuery
     * @param Borrower[] $types
     * @param string|null $grade
     * @param string|null $searchQuery
     * @param bool $onlyWithActiveCheckouts
     * @return PaginatedResult<Borrower>
     */
    public function find(PaginationQuery $paginationQuery, array $types, ?string $grade, ?string $searchQuery = null, bool $onlyWithActiveCheckouts = false): PaginatedResult;

    /**
     * @return string[]
     */
    public function findAllGrades(): array;

    /**
     * @param BorrowerType $type
     * @return Borrower[]
     */
    public function findExternalByType(BorrowerType $type): array;

    /**
     * @param string[] $emailsOrBarcodeIds
     * @return Borrower[]
     */
    public function findAllByEmailOrBarcodeIds(array $emailsOrBarcodeIds): array;

    public function countAll(): int;

    public function persist(Borrower $person): void;

    public function remove(Borrower $person): void;
}
