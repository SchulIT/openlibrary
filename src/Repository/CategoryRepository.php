<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Category;
use Override;

class CategoryRepository extends AbstractRepository implements CategoryRepositoryInterface {

    #[Override]
    public function findAll(): array {
        return $this->em
            ->getRepository(Category::class)
            ->findBy([], ['name' => 'ASC']);
    }


    #[Override]
    public function findByUuid(string $uuid): ?Category {
        return $this->em
            ->getRepository(Category::class)
            ->findOneBy(['uuid' => $uuid]);
    }

    #[Override]
    public function findOneByAbbreviation(string $abbreviation): ?Category {
        return $this->em
            ->getRepository(Category::class)
            ->findOneBy(['abbreviation' => $abbreviation]);
    }

    #[Override]
    public function find(PaginationQuery $paginationQuery, ?string $query = null): PaginatedResult {
        $qb = $this->em->createQueryBuilder()
            ->select('c')
            ->from(Category::class, 'c')
            ->orderBy('c.name', 'ASC');

        if(!empty($query)) {
            $qb->andWhere('c.name LIKE :query')
                ->orWhere('c.abbreviation LIKE :query')
                ->setParameter('query', '%'.$query.'%');
        }

        return PaginatedResult::fromQueryBuilder($qb, $paginationQuery);
    }

    #[Override]
    public function persist(Category $category): void {
        $this->em->persist($category);
        $this->em->flush();
    }

    #[Override]
    public function remove(Category $toRemove, Category $newCategory): void {
        $this->em->createQueryBuilder()
            ->update(Book::class, 'b')
            ->set('b.category', ':new')
            ->where('b.category = :old')
            ->setParameter('new', $newCategory->getId())
            ->setParameter('old', $toRemove->getId())
            ->getQuery()
            ->execute();

        $this->em->remove($toRemove);
        $this->em->flush();
    }
}
