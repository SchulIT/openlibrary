<?php

namespace App\Import\BookMetadata;

use App\Antolin\Cache;
use App\Antolin\Metadata;
use Override;

readonly class AntolinCrawler implements CrawlerInterface {

    public function __construct(private Cache $cache) {

    }

    #[Override]
    public function supports(string $isbn): bool {
        return $this->cache->has($isbn);
    }

    #[Override]
    public function crawl(string $isbn): BookMetadata {
        $antolinMetadata = $this->cache->get($isbn);

        $metadata = new BookMetadata();
        $metadata->isbn = $antolinMetadata->isbn;
        $metadata->title = $antolinMetadata->title;
        $metadata->authors = array_map('trim', explode('; ', $antolinMetadata->author));
        $metadata->publisher = $antolinMetadata->publisher;

        return $metadata;
    }

    private function getAuthors(Metadata $metadata): array {
        $authors = explode(';', $metadata->author);
        $result = [ ];

        foreach($authors as $author) {
            $names = explode(',', $author);
            $names = array_reverse($names);
            $names = array_map('trim', $names);

            $result[] = implode(' ', $names);
        }

        return $result;
    }

    #[Override]
    public function getPriority(): int {
        return 10;
    }
}
