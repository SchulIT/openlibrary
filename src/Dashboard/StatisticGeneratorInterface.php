<?php

namespace App\Dashboard;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(StatisticGeneratorInterface::AUTOCONFIGURE_TAG)]
interface StatisticGeneratorInterface {
    public const string AUTOCONFIGURE_TAG = 'app.dashboard.statistic_generator';

    public function generate(): Statistic|null;
    public function getPriority(): int;
}
