<?php

namespace App\Dashboard;

use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

readonly class DashboardStatistics {

    /**
     * @param StatisticGeneratorInterface[] $generators
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        #[AutowireIterator(StatisticGeneratorInterface::AUTOCONFIGURE_TAG)] private iterable $generators
    ) {

    }

    /**
     * @return Statistic[]
     */
    public function getStatistics(): array {
        $generators = iterator_to_array($this->generators);
        usort($generators, fn(StatisticGeneratorInterface $generatorA, StatisticGeneratorInterface $generatorB): int => $generatorA->getPriority() <=> $generatorB->getPriority());

        $result = [ ];

        foreach($generators as $generator) {
            if(($statistic = $generator->generate()) !== null) {
                $result[] = $statistic;
            }
        }

        return $result;
    }
}
