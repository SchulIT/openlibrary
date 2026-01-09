<?php

namespace App\Repository;

use App\Entity\Borrower;
use App\Entity\Checkout;
use DateTime;

interface CheckoutRepositoryInterface {

    /**
     * @param Borrower $borrower
     * @return Checkout[]
     */
    public function findActiveByBorrower(Borrower $borrower): array;

    public function hasActiveCheckouts(Borrower $borrower): bool;

    public function countActive(): int;

    public function countAll(): int;

    /**
     * @param PaginationQuery $paginationQuery
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @param bool $onlyActive
     * @return PaginatedResult<Checkout>
     */
    public function find(PaginationQuery $paginationQuery, ?DateTime $start = null, ?DateTime $end = null, bool $onlyActive = false, ?string $query = null): PaginatedResult;

    public function persist(Checkout $checkout): void;
    public function remove(Checkout $checkout): void;
}
