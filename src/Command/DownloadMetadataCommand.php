<?php

namespace App\Command;

use App\Book\DownloadMetadataMessage;
use App\Repository\BookRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand('app:book:metadata:download', description: 'Lädt die Metadaten aller Bücher im Bestand herunter.')]
readonly class DownloadMetadataCommand {

    public function __construct(
        private BookRepositoryInterface $bookRepository,
        private MessageBusInterface $messageBus
    ) {

    }

    public function __invoke(SymfonyStyle $io): int {
        $books = $this->bookRepository->findAll();

        $io->section('Füge alle Bücher in die Download-Warteschlange ein');

        foreach($books as $book) {
            $this->messageBus->dispatch(new DownloadMetadataMessage($book->getId()));
        }

        $io->success(sprintf('%d Bücher eingereiht - der Download läuft asynchron, um DoS zu verhindern', count($books)));

        return Command::SUCCESS;
    }
}
