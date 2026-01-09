<?php

namespace App\Dashboard;

use App\Repository\BookRepositoryInterface;
use App\Repository\BorrowerRepositoryInterface;
use App\Repository\CheckoutRepositoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

readonly class BorrowerStatisticGenerator implements StatisticGeneratorInterface {
    public function __construct(
        private AuthorizationCheckerInterface $authorizationChecker,
        private BorrowerRepositoryInterface $borrowerRepository
    ) {

    }

    #[Override]
    public function generate(): Statistic|null {
        if(!$this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return null;
        }

        return new Statistic(
            'dashboard.statistic.borrowers',
            $this->borrowerRepository->countAll()
        );
    }

    #[Override]
    public function getPriority(): int {
        return 4;
    }
}
