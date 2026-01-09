<?php

namespace App\Antolin;

readonly class Metadata {
    public function __construct(public string $bookId,
                                public string $author,
                                public string $title,
                                public string $publisher,
                                public string $isbn) {

    }
}
