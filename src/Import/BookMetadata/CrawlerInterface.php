<?php

namespace App\Import\BookMetadata;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.import.metadata.crawler')]
interface CrawlerInterface {

    /**
     * Returns whether the actual crawler supports the given ISBN.
     *
     * @param string $isbn A valid ISBN-13 number without hyphens
     * @return bool
     */
    public function supports(string $isbn): bool;

    /**
     * Crawls metadata for the given ISBN.
     *
     * @param string $isbn A valid ISBN-13 number without hyphens
     * @return BookMetadata
     */
    public function crawl(string $isbn): BookMetadata;

    public function getPriority(): int;
}