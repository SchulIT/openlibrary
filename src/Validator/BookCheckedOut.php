<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class BookCheckedOut extends Constraint {
    public string $messageNoCheckout = 'The book {{ title }} does not have any active checkout.';
}
