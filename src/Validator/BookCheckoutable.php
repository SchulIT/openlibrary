<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class BookCheckoutable extends Constraint {
    public string $messageNotBorrowable = 'The book {{ title }} is not borrowable.';
    public string $messageActiveCheckout = 'The book {{ title }} has an active checkout.';
}
