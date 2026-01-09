<?php

namespace App\Import\Book;

use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class ValidationFailedException extends Exception implements Throwable {
    public function __construct(public readonly string $id, public readonly ConstraintViolationListInterface $violations, string $message = "", int $code = 0, ?Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
