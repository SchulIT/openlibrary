<?php

namespace App\Http\ValueResolver;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
readonly class MapCsvQueryParameter {
    public function __construct(
        public string $delimiter = ',',
        public ?int $filter = null,
        public int $flags = 0,
    ) {

    }
}
