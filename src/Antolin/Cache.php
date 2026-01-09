<?php

namespace App\Antolin;

use App\Helper\IsbnHelper;
use Psr\SimpleCache\CacheInterface;

readonly class Cache {

    public function __construct(private CacheInterface $cache, private IsbnHelper $isbnHelper) { }

    private function getKey(string $isbn): string {
        return sprintf('antolin.%s', $this->isbnHelper->getCanonicalIsbn($isbn));
    }

    public function clear(string $isbn): void {
        $key = $this->getKey($isbn);

        if($this->cache->has($key)) {
            $this->cache->delete($key);
        }
    }

    public function has(string $isbn): bool {
        return $this->cache->has($this->getKey($isbn));
    }

    public function save(string $isbn, Metadata $metadata): void {
        $this->cache->set($this->getKey($isbn), $metadata);
    }

    public function get(string $isbn): ?Metadata {
        return $this->cache->get($this->getKey($isbn), null);
    }
}
