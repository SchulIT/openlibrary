<?php

namespace App\Command;

use App\Antolin\Downloader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('app:antolin:download')]
readonly class DownloadAntolinMetadataCommand {

    public function __construct(private Downloader $downloader) {

    }

    public function __invoke(SymfonyStyle $io, #[Option('URL zum Herunterladen (optional, nutze Standard-URL, wenn nicht angegeben)', 'url')] string $url = Downloader::Url): int {
        $io->section('Lade Antolin Metadaten herunter und speichere sie im Cache');

        $this->downloader->download($url);

        $io->success('Fertig');
        return Command::SUCCESS;
    }
}
