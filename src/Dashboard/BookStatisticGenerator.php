<?php

namespace App\Dashboard;

use App\Repository\BookRepositoryInterface;
use App\Repository\CheckoutRepositoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

readonly class BookStatisticGenerator implements StatisticGeneratorInterface {
    public function __construct(
        private AuthorizationCheckerInterface $authorizationChecker,
        private BookRepositoryInterface $bookRepository
    ) {

    }

    #[Override]
    public function generate(): Statistic|null {
        if(!$this->authorizationChecker->isGranted('ROLE_BOOKS_ADMIN')) {
            return null;
        }

        return new Statistic(
            'dashboard.statistic.books',
            $this->bookRepository->countAll()
        );
    }

    #[Override]
    public function getPriority(): int {
        return 5;
    }
}
