<?php

namespace App\Notification;

use App\Repository\CheckoutRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

#[AsMessageHandler]
readonly class SendOverdueCheckoutEmailNotificationMessageHandler {

    public function __construct(
        private MailerInterface $mailer,
        private CheckoutRepositoryInterface $checkoutRepository,
        private TranslatorInterface $translator,
        private Environment $twig,
        #[Autowire(env: 'MAILER_FROM')] private string $from,
        #[Autowire(env: 'APP_NAME')] private string $appName
    ) {

    }

    public function __invoke(SendOverdueCheckoutEmailNotificationMessage $message): mixed {
        $checkout = $this->checkoutRepository->findOneById($message->checkoutId);

        if($checkout === null) {
            return sprintf('Ausleihe mit der ID %d nicht gefunden.', $message->checkoutId);
        }

        $mail = new Email()
            ->from($this->from)
            ->to($checkout->getBorrower()->getEmail())
            ->subject(
                $this->translator->trans('overdue_checkout.subject', ['%app%' => $this->appName ], domain: 'notifications')
            )
            ->text(
                $this->twig->render(
                    'notifications/overdue_checkout.txt.twig', [
                        'checkout' => $checkout,
                        'borrower' => $checkout->getBorrower(),
                        'book' => $checkout->getBook(),
                    ]
                )
            );

        $this->mailer->send($mail);

        return 'SENT';
    }
}
