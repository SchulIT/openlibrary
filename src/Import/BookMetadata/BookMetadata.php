<?php

namespace App\Import\BookMetadata;

class BookMetadata {
    public string $isbn;

    public string|null $title = null;

    public string|null $subtitle = null;

    public array $authors = [ ];

    public int|null $year = null;

    public string|null $publisher = null;

    public string|null $image = null;
}
