<?php

namespace App\Import\BookMetadata;

use Exception;
use Throwable;

class CrawlException extends Exception {
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}