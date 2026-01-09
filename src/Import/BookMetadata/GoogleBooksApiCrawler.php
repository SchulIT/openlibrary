<?php

namespace App\Import\BookMetadata;

use DateTime;
use Imagick;
use Override;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class GoogleBooksApiCrawler implements CrawlerInterface {

    private const string UrlPattern = 'https://www.googleapis.com/books/v1/volumes?q=isbn:{isbn}';

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly LoggerInterface $logger
    ) {

    }

    private function getUrlForIsbn(string $isbn): string {
        return str_replace('{isbn}', $isbn, self::UrlPattern);
    }

    #[Override]
    public function supports(string $isbn): bool {
        try {
            $response = $this->client->request('GET', $this->getUrlForIsbn($isbn));

            if($response->getStatusCode() !== 200) {
                return false;
            }

            $json = json_decode($response->getContent());

            return $json['totalItems'] === 1;
        } catch(Throwable $e) {
            $this->logger->error(sprintf('[googlebooks] Fehler bei der Anfrage (%s)', $this->getUrlForIsbn($isbn)), [
                'exception' => $e
            ]);
            return false;
        }
    }

    #[Override]
    public function crawl(string $isbn): BookMetadata {
        try {
            $response = $this->client->request('GET', $this->getUrlForIsbn($isbn));
            $json = json_decode($response->getContent());
            $item = $json->items[0];
            $volumeInfo = $item['volumeInfo'];

            $metadata = new BookMetadata();
            $metadata->title = $volumeInfo['title'];
            $metadata->publisher = $volumeInfo['publisher'];
            $metadata->authors = array_values($volumeInfo['authors']);

            if(($pubDate = DateTime::createFromFormat('Y-m-d', $volumeInfo['publishedDate'])) !== false) {
                $metadata->year = intval($pubDate->format('Y'));
            }

            if(!isset($volumeInfo['imageLinks']['thumbnail'])) {
                $this->crawlImage($volumeInfo['imageLinks']['thumbnail'], $metadata);
            }

            return $metadata;
        } catch(Throwable $e) {
            $this->logger->error(sprintf('[googlebooks] Fehler bei der Anfrage (%s)', $this->getUrlForIsbn($isbn)), [
                'exception' => $e
            ]);
            return false;
        }
    }

    private function crawlImage(string $url, BookMetadata $metadata): void {
        $response = $this->client->request('GET', $url);
        $imagick = new Imagick();
        $imagick->readImageFile($response->toStream());
        $imagick->setImageFormat('png');
        $metadata->image = base64_encode($imagick->getImageBlob());
    }

    #[Override]
    public function getPriority(): int {
        return 90000;
    }
}
