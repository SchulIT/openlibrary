<?php

namespace App\Import\Borrower;

use App\Entity\Borrower;

class Result {
    private array $added = [];
    private array $updated = [];
    private array $removed = [];
    private array $notRemoved = [];

    public function addAdded(Borrower $borrower): void {
        $this->added[] = $borrower;
    }

    public function addUpdated(Borrower $borrower): void {
        $this->updated[] = $borrower;
    }

    public function addRemoved(Borrower $borrower): void {
        $this->removed[] = $borrower;
    }

    public function addNotRemoved(Borrower $borrower): void {
        $this->notRemoved[] = $borrower;
    }

    public function getAdded(): array {
        return $this->added;
    }

    public function getUpdated(): array {
        return $this->updated;
    }

    public function getRemoved(): array {
        return $this->removed;
    }

    public function getNotRemoved(): array {
        return $this->notRemoved;
    }

}
