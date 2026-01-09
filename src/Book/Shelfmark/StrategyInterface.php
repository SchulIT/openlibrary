<?php

namespace App\Book\Shelfmark;

use App\Entity\Book;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(StrategyInterface::AUTOCONFIGURE_TAG)]
interface StrategyInterface {

    public const string AUTOCONFIGURE_TAG = 'app.book.shelfmark.generator_strategy';

    public function generate(Book $book, ?string $parameter): ?string;

    public function getLabelTranslationKey(): string;

    public function getHelpTranslationKey(): string;
}
