<?php

namespace App\Dashboard;

class Statistic {
    public function __construct(
        public string $translationKey,
        public int $count
    ) {

    }
}
