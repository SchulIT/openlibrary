<?php

namespace App\Book;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('metadata')]
readonly class DownloadMetadataMessage {
    public function __construct(public int $bookId) { }
}
