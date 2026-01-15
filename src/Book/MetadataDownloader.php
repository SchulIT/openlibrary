<?php

namespace App\Book;

use App\Entity\Book;
use App\Import\BookMetadata\BookMetadataCrawler;
use App\Repository\BookRepositoryInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Uid\Uuid;
use Throwable;

readonly class MetadataDownloader {

    public const string Mapping = 'covers';

    public function __construct(private BookMetadataCrawler $crawler,
                                private BookRepositoryInterface $bookRepository,
                                #[Autowire('%kernel.project_dir%')] private string $projectDir,
                                #[Autowire('%vich_uploader.mappings%')] private array $vichMappings,
                                private LoggerInterface $logger) {

    }

    public function downloadMetadata(Book $book): void {
        try {
            if ($this->crawler->supports($book->getIsbn())) {
                $metadata = $this->crawler->crawl($book->getIsbn());

                if(empty($metadata->title)) {
                    return; // not real data?!
                }

                $book->setTitle($metadata->title);

                $book->setSubtitle($metadata->subtitle);
                $book->setPublisher($metadata->publisher);
                $book->setYear($metadata->year);
                $book->setAuthors($metadata->authors);

                if (!empty($metadata->image)) {
                    do {
                        $directory = $this->vichMappings[self::Mapping]['upload_destination'];
                        $filename = Uuid::v4()->toString() . '.png';
                        $path = $directory . DIRECTORY_SEPARATOR . $filename;
                    } while (file_exists($path));

                    $handle = fopen($path, 'wb');
                    fwrite($handle, base64_decode($metadata->image));
                    fclose($handle);
                    $book->setCoverFilename($filename);
                }

                $this->bookRepository->persist($book);
            }
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), [
                'exception' => $e
            ]);
        }
    }
}
