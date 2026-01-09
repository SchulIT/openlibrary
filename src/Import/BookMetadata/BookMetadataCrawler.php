<?php

namespace App\Import\BookMetadata;

use App\Helper\IsbnHelper;
use LogicException;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\Validator\Constraints\Isbn;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookMetadataCrawler {

    /** @var CrawlerInterface[] */
    private array $strategies;

    /**
     * @param CrawlerInterface[] $strategiesIterable
     */
    public function __construct(#[AutowireIterator('app.import.metadata.crawler')] iterable $strategiesIterable, private readonly ValidatorInterface $validator, private IsbnHelper $isbnHelper) {
        $this->strategies = iterator_to_array($strategiesIterable);
        usort($this->strategies, fn(CrawlerInterface $a, CrawlerInterface $b) => $a->getPriority() - $b->getPriority());
    }

    /**
     * @throws InvalidIsbnException
     */
    private function throwIfInvalidIsbn(string $isbn): void {
        $violations = $this->validator->validate($isbn, new Isbn());
        if (count($violations) > 0) {
            throw new InvalidIsbnException($violations);
        }
    }

    /**
     * Normalizes the given ISBN to its canonical and ISBN-13 representation (without hyphens).
     * Note: This method does not perform any validation, the input ISBN is supposed to be a valid
     * ISBN-10 or ISBN-13.
     *
     * @param string $isbn Valid ISBN-10 or ISBN-13
     * @return string ISBN-13 without hyphens
     */
    private function normalizeIsbn(string $isbn): string {
        $isbn = $this->isbnHelper->getCanonicalIsbn($isbn);
        return $this->isbnHelper->convertIsbn10To13($isbn);
    }

    /**
     * @throws InvalidIsbnException
     */
    public function supports(string $isbn): bool {
        $this->throwIfInvalidIsbn($isbn);
        $isbn = $this->normalizeIsbn($isbn);

        foreach($this->strategies as $strategy) {
            if($strategy->supports($isbn)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @throws InvalidIsbnException
     * @throws CrawlException
     */
    public function crawl(string $isbn): BookMetadata {
        $this->throwIfInvalidIsbn($isbn);
        $isbn = $this->normalizeIsbn($isbn);

        foreach($this->strategies as $strategy) {
            if($strategy->supports($isbn)) {
                return $strategy->crawl($isbn);
            }
        }

        throw new LogicException('This code should not be executed.');
    }
}