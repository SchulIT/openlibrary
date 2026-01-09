<?php

namespace App\Checkout;

use App\Entity\Book;
use App\Entity\Borrower;
use App\Validator\BookCheckoutable;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

class CheckoutRequest {
    #[Assert\NotNull]
    #[BookCheckoutable]
    public Book $book;

    #[Assert\NotNull]
    public DateTime $expectedEnd;

    #[Assert\NotNull]
    public Borrower $borrower;
}
