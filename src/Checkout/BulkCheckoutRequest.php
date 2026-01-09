<?php

namespace App\Checkout;

use App\Entity\Book;
use App\Entity\Borrower;
use App\Validator\BookCheckoutable;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotNull;

class BulkCheckoutRequest {

    #[Assert\NotNull]
    public ?Borrower $borrower = null;

    #[Assert\NotNull]
    public ?DateTime $expectedEnd = null;

    /**
     * @var Book[]
     */
    #[Assert\Count(min: 1)]
    #[Assert\All(
        constraints: [ new NotNull(), new BookCheckoutable() ]
    )]
    public array $books = [ ];
}
