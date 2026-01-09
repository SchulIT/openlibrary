<?php

namespace App\Validator;

use App\Checkout\CheckoutManager;
use App\Checkout\CheckoutStatus;
use App\Entity\Book;
use Override;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class BookCheckoutableValidator extends ConstraintValidator {

    public function __construct(
        private CheckoutManager $checkoutManager
    ) {

    }

    #[Override]
    public function validate(mixed $value, Constraint $constraint): void {
        if(!$constraint instanceof BookCheckoutable) {
            throw new UnexpectedTypeException($constraint, BookCheckoutable::class);
        }

        if(!$value instanceof Book) {
            throw new UnexpectedTypeException($value, Book::class);
        }

        $status = $this->checkoutManager->getStatus($value);

        if($status === CheckoutStatus::CheckedOut) {
            $this->context
                ->buildViolation($constraint->messageActiveCheckout)
                ->setParameter('{{ title }}', $value->getTitle())
                ->addViolation();
        } else if($status === CheckoutStatus::NotAvailable) {
            $this->context
                ->buildViolation($constraint->messageNotBorrowable)
                ->setParameter('{{ title }}', $value->getTitle())
                ->addViolation();
        }
    }
}
