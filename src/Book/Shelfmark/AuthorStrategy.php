<?php

namespace App\Book\Shelfmark;

use App\Entity\Book;
use Override;

class AuthorStrategy implements StrategyInterface {

    #[Override]
    public function generate(Book $book, ?string $parameter): ?string {
        $chars = 0;

        if(is_numeric($parameter)) {
            $chars = intval($parameter);
        }

        if($chars < 0) {
            $chars = 0;
        }

        $author = array_first($book->getAuthors());

        if($author === null) {
            return null;
        }

        $pos = strpos($author->getName(), ',');

        if($pos !== false) {
            $author = trim(mb_substr($author->getName(), 0, $pos));
        } else {
            $pos = strrpos($author->getName(), ' ');
            if($pos !== false) {
                $author = trim(mb_substr($author->getName(), $pos));
            }
        }

        if($chars === 0) {
            return $author;
        }

        return mb_substr($author, 0, $chars);
    }

    #[Override]
    public function getLabelTranslationKey(): string {
        return 'books.shelfmark.strategies.author.label';
    }

    #[Override]
    public function getHelpTranslationKey(): string {
        return 'books.shelfmark.strategies.author.help';
    }
}
