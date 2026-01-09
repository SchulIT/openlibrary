<?php

namespace App\Http\ValueResolver;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
readonly class MapDateTimeQueryParameter {
    public function __construct(
        public string $format = 'Y-m-d'
    ) {

    }
}
