<?php

namespace App\Import\BookMetadata;

use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class InvalidIsbnException extends Exception {
    public function __construct(private readonly ConstraintViolationListInterface $violations, string $message = "", int $code = 0, ?Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function getViolations(): ConstraintViolationListInterface {
        return $this->violations;
    }
}