<?php

namespace App\Command;

use App\Book\BarcodeCleanup;
use App\Book\TitleCleanup;
use App\Repository\BookRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('app:cleanup:title')]
class TitleCleanupCommand {
    public function __construct(
        private readonly BookRepositoryInterface $bookRepository, private readonly TitleCleanup $cleanup
    ) { }

    public function __invoke(SymfonyStyle $io): int {
        $books = $this->bookRepository->findAll();

        ProgressBar::setFormatDefinition('custom', ' %current%/%max% -- %message%');
        $progressBar = new ProgressBar($io, count($books));
        $progressBar->setFormat('custom');
        $progressBar->setMessage('');
        $progressBar->start();

        $this->bookRepository->beginTransaction();

        foreach($books as $book) {
            $progressBar->setMessage('Ändere Barcode ID: ' . $book->getIsbn());

            $book->setTitle($this->cleanup->cleanup($book->getTitle()));
            $book->setSubtitle($this->cleanup->cleanup($book->getSubtitle()));

            $this->bookRepository->persist($book);

            $progressBar->advance();
        }

        $this->bookRepository->commit();
        $progressBar->finish();

        $io->success('Alle Barcodes erfolgreich aufgeräumt.');

        return Command::SUCCESS;
    }
}
