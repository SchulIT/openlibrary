<?php

namespace App\Book\Shelfmark;

use App\Entity\Book;
use Override;

class CategoryNameStrategy implements StrategyInterface {

    #[Override]
    public function generate(Book $book, ?string $parameter): ?string {
        return $book->getCategory()->getName();
    }

    #[Override]
    public function getLabelTranslationKey(): string {
        return 'books.shelfmark.strategies.category.label';
    }

    #[Override]
    public function getHelpTranslationKey(): string {
        return 'books.shelfmark.strategies.category.help';
    }
}
