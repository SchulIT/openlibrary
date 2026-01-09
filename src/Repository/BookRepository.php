<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Category;
use Override;

class BookRepository extends AbstractTransactionalRepository implements BookRepositoryInterface {

    #[Override]
    public function findOneByBarcodeId(string $barcodeId): ?Book {
        return $this->em
            ->getRepository(Book::class)
            ->findOneBy(['barcodeId' => $barcodeId]);
    }

    #[Override]
    public function find(PaginationQuery $paginationQuery, ?string $searchQuery = null, ?Category $category = null, bool $onlyListed = false): PaginatedResult {
        $qb = $this->em->createQueryBuilder()
            ->select(['b'])
            ->from(Book::class, 'b')
            ->orderBy('b.title', 'ASC');

        if(!empty($searchQuery)) {
            $qb->andWhere(
                $qb->expr()->orX(
                    'b.title LIKE :searchQuery',
                    'b.subtitle LIKE :searchQuery',
                    'b.isbn LIKE :searchQuery',
                    'b.shelfmark LIKE :searchQuery',
                    'b.topic LIKE :searchQuery',
                )
            )
                ->setParameter('searchQuery', '%' . $searchQuery . '%');
        }

        if($category !== null) {
            $qb->andWhere('b.category = :category')
                ->setParameter('category', $category->getId());
        }

        if($onlyListed === true) {
            $qb->andWhere('b.isListed = true');
        }

        return PaginatedResult::fromQueryBuilder($qb, $paginationQuery);
    }

    #[Override]
    public function findAll(): array {
        return $this->em
            ->getRepository(Book::class)
            ->findAll();
    }

    #[Override]
    public function persist(Book $book): void {
        $this->em->persist($book);
        $this->flushIfNotInTransaction();
    }

    #[Override]
    public function remove(Book $book): void {
        $this->em->remove($book);
        $this->flushIfNotInTransaction();
    }
}
