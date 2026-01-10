<?php

namespace App\Command;

use App\Notification\SendOverdueCheckoutEmailNotificationMessage;
use App\Repository\CheckoutRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Scheduler\Attribute\AsCronTask;

#[AsCommand('app:notify:overdue', description: 'Benachrichtigt Entleiher, deren Ausleihen überfällig sind.')]
#[AsCronTask('@daily')]
readonly class NotifyOverdueBorrowersCommand {

    public function __construct(
        private CheckoutRepositoryInterface $checkoutRepository,
        private MessageBusInterface $messageBus,
    ) { }

    public function __invoke(SymfonyStyle $io): int {
        $checkouts = $this->checkoutRepository->findAllOverdue();

        foreach($checkouts as $checkout) {
            $this->messageBus->dispatch(new SendOverdueCheckoutEmailNotificationMessage($checkout->getId()));
        }

        $io->success(
            sprintf('%d Benachrichtigungen versendet', count($checkouts))
        );

        return Command::SUCCESS;
    }
}
