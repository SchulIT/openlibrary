<?php

namespace App\Command;

use App\Book\MetadataDownloader;
use App\Repository\BookRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('app:book:metadata:download', description: 'Lädt die Metadaten aller Bücher im Bestand herunter.')]
readonly class DownloadMetadataCommand {

    public function __construct(private MetadataDownloader $downloader, private BookRepositoryInterface $bookRepository) {

    }

    public function __invoke(SymfonyStyle $ui, #[Option('Skips the first N books', 'skip')] int|null $skip = null): int {
        $books = $this->bookRepository->findAll();

        ProgressBar::setFormatDefinition('custom', ' %current%/%max% -- %message%');
        $progressBar = new ProgressBar($ui, count($books));
        $progressBar->setFormat('custom');
        $progressBar->setMessage('');
        $progressBar->start();
        $i = 0;

        foreach($books as $book) {
            if($skip !== null && $i < $skip) {
                $progressBar->advance();
                $i++;
                continue;
            }
            $progressBar->setMessage('Herunterladen ' . $book->getIsbn());
            $this->downloader->downloadMetadata($book);

            $progressBar->advance();
            $i++;

            sleep(2);
        }

        $progressBar->finish();

        return Command::SUCCESS;
    }
}
