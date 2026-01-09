<?php

namespace App\Antolin;

use League\Csv\Exception;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class Downloader {
    public const string Url = "https://antolin.westermann.de/all/downloads/antolingesamt-utf8.csv";

    public function __construct(private HttpClientInterface $client, private Cache $cache) {

    }

    /**
     * @throws DownloadException
     * @throws InvalidArgument
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function download(string $url): void {
        try {
            $response = $this->client->request('GET', $url);
            if ($response->getStatusCode() !== 200) {
                throw new DownloadException('Antwort des Servers ist nicht HTTP 200, sondern HTTP ' . $response->getStatusCode());
            }

            $csv = $response->getContent();
            $reader = Reader::fromString($csv);
            $reader->setDelimiter(';');
            $reader->setHeaderOffset(0);

            foreach ($reader->getRecords() as $row) {
                $metadata = new Metadata(
                    $row['book_id'],
                    $row['Autor'],
                    $row['Titel'],
                    $row['Verlag'],
                    $row['ISBN-13']
                );

                $this->cache->save($row['ISBN-13'], $metadata);
            }
        } catch (TransportExceptionInterface $e) {
            throw new DownloadException($e->getMessage(), $e->getCode(), $e);
        }
    }

}
