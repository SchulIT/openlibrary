<?php

namespace App\Repository;

use App\Entity\Borrower;
use App\Entity\BorrowerType;
use Override;

class BorrowerRepository extends AbstractTransactionalRepository implements BorrowerRepositoryInterface {

    public function findOneById(int $id): ?Borrower {
        return $this->em->getRepository(Borrower::class)
            ->findOneBy([
                'id' => $id
            ]);
    }

    public function findByBarcodeId(string $barcodeId): ?Borrower {
        return $this->em->getRepository(Borrower::class)
            ->findOneBy([
                'barcodeId' => $barcodeId
            ]);
    }

    public function find(PaginationQuery $paginationQuery, array $types, ?string $grade, ?string $searchQuery = null, bool $onlyWithActiveCheckouts = false): PaginatedResult {

        $qb = $this->em->createQueryBuilder()
            ->select(['p'])
            ->from(Borrower::class, 'p')
            ->where('p.type IN (:types)')
            ->setParameter('types', $types)
            ->orderBy('p.lastname', 'asc')
            ->addOrderBy('p.firstname', 'asc');

        if($grade !== null) {
            $qb->andWhere('p.grade = :grade')->setParameter('grade', $grade);
        }

        if($onlyWithActiveCheckouts === true) {
            $qbInner = $this->em->createQueryBuilder()
                ->select('bInner.id')
                ->from(Borrower::class, 'bInner')
                ->innerJoin('bInner.checkouts', 'cInner')
                ->where('cInner.end IS NULL');

            $qb->andWhere(
                $qb->expr()->in('p.id', $qbInner->getDQL())
            );
        }

        if(!empty($searchQuery)) {
            $qb->andWhere(
                $qb->expr()->orX(
                    'p.barcodeId = :searchQuery',
                    'p.firstname LIKE :searchQuery',
                    'p.lastname LIKE :searchQuery',
                    'p.email LIKE :searchQuery'
                )
            )
                ->setParameter('searchQuery', '%'.$searchQuery.'%');
        }

        return PaginatedResult::fromQueryBuilder($qb, $paginationQuery);
    }

    public function findAll(): array {
        return $this->em->getRepository(Borrower::class)
            ->findBy(
                [],
                [
                    'lastname' => 'asc',
                    'firstname' => 'asc'
                ]
            );
    }

    public function findExternalByType(BorrowerType $type): array {
        return $this->em->createQueryBuilder()
            ->select('b')
            ->from(Borrower::class, 'b')
            ->where('b.type = :type')
            ->andWhere('b.barcodeId IS NOT NULL')
            ->setParameter('type', $type)
            ->orderBy('b.lastname', 'asc')
            ->addOrderBy('b.firstname', 'asc')
            ->getQuery()
            ->getResult();
    }

    public function persist(Borrower $person): void {
        $this->em->persist($person);
        $this->flushIfNotInTransaction();
    }

    public function remove(Borrower $person): void {
        $this->em->remove($person);
        $this->flushIfNotInTransaction();
    }

    public function findAllByEmailOrBarcodeIds(array $emailsOrBarcodeIds): array {
        return $this->em->createQueryBuilder()
            ->select('b')
            ->from(Borrower::class, 'b')
            ->where('b.email IN (:emailsOrBarcodeIds)')
            ->orWhere('b.barcodeId IN (:emailsOrBarcodeIds)')
            ->setParameter('emailsOrBarcodeIds', $emailsOrBarcodeIds)
            ->getQuery()
            ->getResult();
    }

    #[Override]
    public function findAllGrades(): array {
        return $this->em->createQueryBuilder()
            ->select('DISTINCT b.grade')
            ->from(Borrower::class, 'b')
            ->where('b.grade IS NOT NULL')
            ->orderBy('b.grade', 'asc')
            ->getQuery()
            ->getSingleColumnResult();
    }
}
