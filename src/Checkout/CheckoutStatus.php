<?php

namespace App\Checkout;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum CheckoutStatus: string implements TranslatableInterface {
    case Available = 'available';
    case NotAvailable = 'not_available';
    case CheckedOut = 'checked_out';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string {
        $key = sprintf('checkout_status.%s', $this->value);
        return $translator->trans($key, domain: 'enums');
    }
}