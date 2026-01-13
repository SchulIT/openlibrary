<?php

namespace App\Repository;

namespace App\Repository;

class OrderBy {

    public const array AllowedOrderDirections = [ 'asc', 'desc' ];
    public const string DefaultOrderDirection = 'asc';

    /**
     * @param array $allowedColumnNames
     * @param string|null $columnName
     * @param string $order
     */
    public function __construct(
        public readonly array $allowedColumnNames,
        public string|null $columnName,
        public string $order = self::DefaultOrderDirection
    ) { }


}
