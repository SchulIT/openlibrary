<?php

namespace App\Dashboard;

use App\Repository\CheckoutRepositoryInterface;
use DateTime;
use Override;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

readonly class OverdueCheckoutsGenerator implements StatisticGeneratorInterface {

    public function __construct(
        private AuthorizationCheckerInterface $authorizationChecker,
        private CheckoutRepositoryInterface $checkoutRepository,
        private ClockInterface $clock
    ) {

    }

    #[Override]
    public function generate(): Statistic|null {
        if(!$this->authorizationChecker->isGranted('ROLE_LENDER')) {
            return null;
        }

        return new Statistic(
            'dashboard.statistic.overdue_checkouts',
            $this->checkoutRepository->countOverdue(DateTime::createFromImmutable($this->clock->now()))
        );
    }

    #[Override]
    public function getPriority(): int {
        return 3;
    }
}
