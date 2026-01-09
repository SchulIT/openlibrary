<?php

namespace App\Dashboard;

use App\Repository\CheckoutRepositoryInterface;
use Override;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

readonly class CheckoutsStatisticGenerator implements StatisticGeneratorInterface {

    public function __construct(
        private AuthorizationCheckerInterface $authorizationChecker,
        private CheckoutRepositoryInterface $checkoutRepository
    ) {

    }

    #[Override]
    public function generate(): Statistic|null {
        if(!$this->authorizationChecker->isGranted('ROLE_LENDER')) {
            return null;
        }

        return new Statistic(
            'dashboard.statistic.checkouts',
            $this->checkoutRepository->countAll()
        );
    }

    #[Override]
    public function getPriority(): int {
        return 1;
    }
}
