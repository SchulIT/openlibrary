<?php

namespace App\Http\ValueResolver;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
readonly class MapOrderByQueryParameter {
    public function __construct(
        public array $allowedColumnNames,
        public string $defaultColumnName,
        public string|null $columnParameterName = 'sort',
        public string|null $orderParameterName = 'order'
    ) { }
}
