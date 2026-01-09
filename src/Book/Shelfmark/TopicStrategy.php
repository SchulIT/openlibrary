<?php

namespace App\Book\Shelfmark;

use App\Entity\Book;
use Override;

class TopicStrategy implements StrategyInterface {

    #[Override]
    public function generate(Book $book, ?string $parameter): ?string {
        return $book->getTopic();
    }

    #[Override]
    public function getLabelTranslationKey(): string {
        return 'books.shelfmark.strategies.topic.label';
    }

    #[Override]
    public function getHelpTranslationKey(): string {
        return 'books.shelfmark.strategies.topic.help';
    }
}
