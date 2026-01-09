<?php

namespace App\Dashboard;

use App\Repository\CheckoutRepositoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

readonly class ActiveCheckoutsStatisticGenerator implements StatisticGeneratorInterface {
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
            'dashboard.statistic.active_checkouts',
            $this->checkoutRepository->countActive()
        );
    }

    #[Override]
    public function getPriority(): int {
        return 2;
    }
}
