<?php

namespace App\Book;

use App\Entity\Book;

class BarcodeCleanup {
    public function cleanup(Book $book): void {
        $barcodeId = $book->getBarcodeId();

        if(empty($barcodeId)) {
            return;
        }

        $barcode = str_replace(' ', '', $barcodeId);
        $book->setBarcodeId($barcode);
    }
}
