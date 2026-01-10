<?php

namespace App\Notification;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
readonly class SendOverdueCheckoutEmailNotificationMessage {
    public function __construct(public int $checkoutId) {}
}
