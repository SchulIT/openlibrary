<?php

namespace App\Repository;

use App\Entity\LabelTemplate;

class LabelRepository extends AbstractRepository implements LabelRepositoryInterface {

    public function findAll(): array {
        return $this->em->getrepository(LabelTemplate::class)->findBy([], [
            'name' => 'asc'
        ]);
    }

    public function find(PaginationQuery $paginationQuery): PaginatedResult {
        $qb = $this->em->createQueryBuilder()
            ->select('l')
            ->from(LabelTemplate::class, 'l')
            ->orderBy('l.name', 'asc');

        return PaginatedResult::fromQueryBuilder($qb, $paginationQuery);
    }

    public function persist(LabelTemplate $label): void {
        $this->em->persist($label);
        $this->em->flush();
    }

    public function remove(LabelTemplate $label): void {
        $this->em->remove($label);
        $this->em->flush();
    }
}
