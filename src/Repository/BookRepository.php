<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Category;
use Doctrine\ORM\QueryBuilder;
use Override;

class BookRepository extends AbstractTransactionalRepository implements BookRepositoryInterface {

    public const array AllowedOrderByColumns = [
        'title',
        'barcode',
        'shelfmark',
        'isbn'
    ];

    public const string DefaultOrderByColumn = 'title';

    #[Override]
    public function findOneByBarcodeId(string $barcodeId): ?Book {
        return $this->em
            ->getRepository(Book::class)
            ->findOneBy(['barcodeId' => $barcodeId]);
    }

    #[Override]
    public function find(PaginationQuery $paginationQuery, OrderBy $orderBy, ?string $searchQuery = null, ?Category $category = null, bool $onlyListed = false): PaginatedResult {
        $qb = $this->em->createQueryBuilder()
            ->select(['b'])
            ->from(Book::class, 'b');

        $this->applyOrderBy($qb, $orderBy, 'b');


        if(!empty($searchQuery)) {
            $qb->andWhere(
                $qb->expr()->orX(
                    'b.title LIKE :searchQuery',
                    'b.subtitle LIKE :searchQuery',
                    'b.isbn LIKE :searchQuery',
                    'b.shelfmark LIKE :searchQuery',
                    'b.topic LIKE :searchQuery',
                    'b.barcodeId LIKE :searchQuery',
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

        return PaginatedResult::fromQueryBuilder($qb, $paginationQuery, $orderBy);
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

    #[Override]
    public function countAll(): int {
        return $this->em->createQueryBuilder()
            ->select('COUNT(b)')
            ->from(Book::class, 'b')
            ->getQuery()
            ->getSingleScalarResult();
    }

    private function applyOrderBy(QueryBuilder $qb, OrderBy $orderBy, string $prefix): QueryBuilder {
        $columnName = match ($orderBy->columnName) {
            'title' => 'title',
            'barcode' => 'barcodeId',
            'shelfmark' => 'shelfmark',
            'isbn' => 'isbn',
            default => self::DefaultOrderByColumn
        };

        $qb->orderBy(
            sprintf('%s.%s', $prefix, $columnName),
            $orderBy->order
        );

        return $qb;
    }
}
