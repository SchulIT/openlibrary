<?php

namespace App\Helper;

use Exception;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraints\Isbn;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class IsbnHelper {

    public function __construct(private readonly ValidatorInterface $validator) {

    }

    public function isValidIsbn($isbn): bool {
        return $this->validator->validate($isbn, new Isbn())->count() === 0;
    }

    public function isIsbn13($isbn): bool {
        return $this->isValidIsbn($isbn)
            && strlen($this->getCanonicalIsbn($isbn)) === 13;
    }

    public function isIsbn10($isbn): bool {
        return $this->isValidIsbn($isbn)
            && strlen($this->getCanonicalIsbn($isbn)) === 10;
    }

    public function getCanonicalIsbn(string $isbn): string {
        return str_replace('-', '', $isbn);
    }

    /**
     * Convert canonical ISBN13 to hyphenated version, e.g. 9783127338515 => 978-3-12-733851-5.
     *
     * @param string $isbn ISBN13, e.g. 9783127338515
     * @param string[] $prefixes Hyphenated prefix, e.g. 978-3-12
     * @return string Returns hyphenated version, e.g. 978-3-12-733851-5
     */
    public function getHyphenatedIsbn13(string $isbn, string ...$prefixes): string {
        if (!$this->isIsbn13($isbn)) {
            throw new InvalidArgumentException('$isbn ist keine ISBN-13');
        }

        foreach($prefixes as $prefix) {
            if(!str_starts_with($this->getCanonicalIsbn($isbn), $this->getCanonicalIsbn($prefix))) {
                continue;
            }

            $prefix = rtrim($prefix, '-');
            $isbn = $this->getCanonicalIsbn($isbn);
            $canonicalPrefix = $this->getCanonicalIsbn($prefix);

            $middleSection = substr($isbn, strlen($canonicalPrefix), -1);
            $checkDigit = substr($isbn, -1);

            return sprintf('%s-%s-%s', $prefix, $middleSection, $checkDigit);
        }

        throw new InvalidArgumentException('Kein gültiger ISBN-Präfix.');
    }

    public function hasPrefix(string $isbn, string $prefix): bool {
        $canonicalIsbn = $this->getCanonicalIsbn($isbn);
        $canonicalPrefix = $this->getCanonicalIsbn($prefix);

        return str_starts_with($canonicalIsbn, $canonicalPrefix);
    }

    public function convertIsbn10To13(string $isbn): string {
        if($this->isIsbn13($isbn)) {
            return $this->getCanonicalIsbn($isbn);
        }

        if($this->isIsbn10($isbn) !== true) {
            throw new InvalidArgumentException('$isbn ist keine ISBN-10');
        }

        $isbn = $this->getCanonicalIsbn($isbn);
        $isbn13 = '978' . substr($isbn, 0, -1); // drop last digit of ISBN-10

        // calculate check digit
        $sum = 0;
        for($idx = 0; $idx < strlen($isbn) - 1; $idx++) { // skip last digit!
            $digit = intval($isbn[$idx]);
            $multiplier = $idx % 2 === 0 ? 1 : 3;

            $sum += ($digit * $multiplier);
        }

        $lastDigitOfSum = $sum % 10;

        if($lastDigitOfSum === 0) {
            $checkDigit = 0;
        } else {
            $checkDigit = 10 - $lastDigitOfSum;
        }

        return $isbn13 . $checkDigit;
    }
}