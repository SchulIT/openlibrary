<?php

namespace App\Checkout;

use App\Entity\Book;
use App\Entity\Borrower;
use App\Entity\Checkout;
use App\Repository\BookRepositoryInterface;
use App\Repository\CheckoutRepositoryInterface;
use DateTime;

readonly class CheckoutManager {

    public function __construct(
        private CheckoutRepositoryInterface $repository,
        private CheckoutRepositoryInterface $checkoutRepository,
        private BookRepositoryInterface $bookRepository
    ) {     }

    public function bulkCheckout(BulkCheckoutRequest $request): void {
        foreach($request->books as $book) {
            $singleRequest = new CheckoutRequest();
            $singleRequest->book = $book;
            $singleRequest->borrower = $request->borrower;
            $singleRequest->expectedEnd = $request->expectedEnd;

            $this->checkout($singleRequest);
        }
    }

    public function checkout(CheckoutRequest $checkoutRequest): void {
        if($checkoutRequest->book->isBorrowable() === false) {
            return;
        }

        /** @var Checkout|null $lastCheckout */
        $lastCheckout = $checkoutRequest->book->getCheckouts()->first() ?? null;

        if($lastCheckout instanceof Checkout && $lastCheckout->getEnd() === null && $lastCheckout->getBorrower()->getId() === $checkoutRequest->borrower->getId()) {
            // no need for checkout
            return;
        }

        $this->return($checkoutRequest->book);

        $newCheckout = (new Checkout())
            ->setBook($checkoutRequest->book)
            ->setBorrower($checkoutRequest->borrower)
            ->setStart(new DateTime())
            ->setExpectedEnd($checkoutRequest->expectedEnd);

        $this->repository->persist($newCheckout);
    }

    /**
     * @param BulkReturnRequest $request
     * @return Borrower|null
     */
    public function bulkReturn(BulkReturnRequest $request): ?Borrower {
        $borrowers = [ ];
        $borrowersCount = [ ];

        foreach($request->books as $copy) {
            $borrower = $this->return($copy);

            if($borrower === null) {
                continue;
            }

            $borrowers[$borrower->getId()] = $borrower;

            if(!isset($borrowersCount[$borrower->getId()])) {
                $borrowersCount[$borrower->getId()] = 0;
            }

            $borrowersCount[$borrower->getId()]++;
        }

        arsort($borrowersCount);
        $firstKey = array_key_first($borrowersCount);

        return $borrowers[$firstKey] ?? null;
    }

    public function return(Book $book): ?Borrower {
        /** @var Checkout|null $lastCheckout */
        $lastCheckout = $book->getCheckouts()->first() ?? null;

        if($lastCheckout === null || $lastCheckout === false) {
            return null;
        }

        $lastCheckout->setEnd(new DateTime());
        $this->repository->persist($lastCheckout);

        return $lastCheckout->getBorrower();
    }

    public function getStatus(Book $book): CheckoutStatus {
        if($book->isBorrowable() === false) {
            return CheckoutStatus::NotAvailable;
        }

        if($book->getCheckouts()->count() === 0) {
            return CheckoutStatus::Available;
        }

        $latestCheckout = $book->getCheckouts()->first();

        if($latestCheckout === null) {
            return CheckoutStatus::Available;
        }

        if($latestCheckout->getEnd() === null) {
            return CheckoutStatus::CheckedOut;
        }

        return CheckoutStatus::Available;
    }

    public function isCheckedOut(Book $book): bool {
        return $this->getStatus($book) === CheckoutStatus::CheckedOut;
    }

    public function isAvailable(Book $book): bool {
        return $this->getStatus($book) === CheckoutStatus::Available;
    }

    public function endAllActiveCheckoutsForBorrower(Borrower $borrower): void {
        foreach($this->checkoutRepository->findActiveByBorrower($borrower) as $checkout) {
            $checkout->setEnd(new DateTime());
            $checkout->appendComment('---\nAusleihe beendet, da Entleiher gelöscht wurde\n---');
            $this->repository->persist($checkout);

            $book = $checkout->getBook();
            $book->setIsBorrowable(false);
            $book->appendComment(sprintf('---\nBuch wurde vor dem Löschen des Entleihers %s, %s (ID: %s) nicht zurückgegeben.\n---', $borrower->getLastname(), $borrower->getFirstname(), $borrower->getBarcodeId()));
            $this->bookRepository->persist($book);
        }
    }
}
