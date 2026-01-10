<?php

namespace App\Repository;

use App\Entity\Borrower;
use App\Entity\Checkout;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Symfony\Component\Clock\ClockInterface;

class CheckoutRepository extends AbstractRepository implements CheckoutRepositoryInterface {

    public function __construct(EntityManagerInterface $em, private readonly ClockInterface $clock) {
        parent::__construct($em);
    }

    public function persist(Checkout $checkout): void {
        $this->em->persist($checkout);
        $this->em->flush();
    }

    public function remove(Checkout $checkout): void {
        $this->em->remove($checkout);
        $this->em->flush();
    }

    public function findActiveByBorrower(Borrower $borrower): array {
        return $this->em->createQueryBuilder()
            ->select(['c'])
            ->from(Checkout::class, 'c')
            ->leftJoin('c.borrower', 'b')
            ->where('c.borrower = :borrower')
            ->andWhere('c.end IS NULL')
            ->setParameter('borrower', $borrower)
            ->getQuery()
            ->getResult();
    }

    public function hasActiveCheckouts(Borrower $borrower): bool {
        return $this->em->createQueryBuilder()
            ->select('COUNT(DISTINCT c.id)')
            ->from(Checkout::class, 'c')
            ->leftJoin('c.borrower', 'b')
            ->where('c.borrower = :borrower')
            ->andWhere('c.end IS NULL')
            ->setParameter('borrower', $borrower)
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }

    public function countActive(): int {
        return $this->em->createQueryBuilder()
            ->select('COUNT(1)')
            ->from(Checkout::class, 'c')
            ->where('c.end IS NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countAll(): int {
        return $this->em->createQueryBuilder()
            ->select('COUNT(1)')
            ->from(Checkout::class, 'c')
            ->getQuery()
            ->getSingleScalarResult();
    }

    #[Override]
    public function find(PaginationQuery $paginationQuery, ?DateTime $start = null, ?DateTime $end = null, bool $onlyActive = false, bool $onlyOverdue = false, ?string $query = null): PaginatedResult {
        $qb = $this->em->createQueryBuilder()
            ->select(['c', 'b'])
            ->from(Checkout::class, 'c')
            ->leftJoin('c.borrower', 'b')
            ->leftJoin('c.book', 'bo')
            ->orderBy('c.expectedEnd', 'DESC');

        if($start !== null) {
            $qb->andWhere('c.start >= :start')
                ->setParameter('start', $start);
        }

        if($end !== null) {
            $qb->andWhere('c.start <= :end')
                ->setParameter('end', $end);
        }

        if($onlyActive === true) {
            $qb->andWhere('c.end IS NULL');
        }

        if($onlyOverdue === true) {
            $qb->andWhere('c.expectedEnd <= :today')
                ->setParameter('today', $this->clock->now());
        }

        if(!empty($query)) {
            $qb->andWhere(
                $qb->expr()->orX(
                    'b.firstname LIKE :query',
                    'b.lastname LIKE :query',
                    'b.email LIKE :query',
                    'b.barcodeId LIKE :query',
                    'bo.title LIKE :query',
                    'bo.barcodeId LIKE :query'
                )
            )
                ->setParameter('query', '%'.$query.'%');
        }

        return PaginatedResult::fromQueryBuilder($qb, $paginationQuery);
    }

    #[Override]
    public function countOverdue(DateTime $today): int {
        return $this->em->createQueryBuilder()
            ->select('COUNT(c.id)')
            ->from(Checkout::class, 'c')
            ->where('c.end IS NULL')
            ->andwhere('c.expectedEnd <= :today')
            ->setParameter('today', $today)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
