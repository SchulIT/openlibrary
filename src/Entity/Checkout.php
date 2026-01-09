<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Checkout {
    use IdTrait;
    use UuidTrait;

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'checkouts')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private Book $book;

    #[ORM\ManyToOne(targetEntity: Borrower::class, inversedBy: 'checkouts')]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Borrower $borrower = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull]
    private ?DateTime $start;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull]
    private ?DateTime $expectedEnd;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $end;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment;

    use TimestampableOnCreateTrait;
    use TimestampableOnUpdateTrait;

    public function __construct() {
        $this->uuid = Uuid::v4()->toString();
    }

    public function getBook(): Book {
        return $this->book;
    }

    public function setBook(Book $book): Checkout {
        $this->book = $book;
        return $this;
    }

    public function getBorrower(): ?Borrower {
        return $this->borrower;
    }

    public function setBorrower(?Borrower $borrower): Checkout {
        $this->borrower = $borrower;
        return $this;
    }

    public function getStart(): DateTime {
        return $this->start;
    }

    public function setStart(DateTime $start): Checkout {
        $this->start = $start;
        return $this;
    }

    public function getExpectedEnd(): DateTime {
        return $this->expectedEnd;
    }

    public function setExpectedEnd(DateTime $expectedEnd): Checkout {
        $this->expectedEnd = $expectedEnd;
        return $this;
    }

    public function getEnd(): ?DateTime {
        return $this->end;
    }

    public function setEnd(?DateTime $end): Checkout {
        $this->end = $end;
        return $this;
    }

    public function getComment(): ?string {
        return $this->comment;
    }

    public function appendComment(string $comment, string $delimiter = '\n'): Checkout {
        if(empty($this->comment)) {
            $this->comment = trim($comment);
        } else {
            $this->comment .= $delimiter . $comment;
        }

        return $this;
    }

    public function setComment(?string $comment): Checkout {
        $this->comment = $comment;
        return $this;
    }

    public function isOverdue(DateTimeInterface $today): bool {
        return $this->end === null && $this->expectedEnd < $today;
    }

    public function wasReturnedLate(): bool {
        if($this->end === null) {
            return false;
        }

        return $this->expectedEnd < $this->end;
    }


}
