<?php

namespace App\Book;

use App\Repository\BookRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class DownloadMetadataMessageHandler {
    public function __construct(
        private  BookRepositoryInterface $bookRepository,
        private MetadataDownloader $metadataDownloader
    ) {

    }

    public function __invoke(DownloadMetadataMessage $message): void {
        $book = $this->bookRepository->findOneById($message->bookId);

        if($book === null) {
            return;
        }

        $this->metadataDownloader->downloadMetadata($book);
    }
}
