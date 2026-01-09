<?php

namespace App\Checkout;

use App\Entity\Book;
use App\Validator\BookCheckedOut;
use Symfony\Component\Validator\Constraints as Assert;

class BulkReturnRequest {
    /**
     * @var Book[]
     */
    #[Assert\Count(min: 1)]
    #[Assert\All(
        constraints: [
            new Assert\NotNull(),
            new BookCheckedOut()
        ]
    )]
    public array $books = [ ];
}
