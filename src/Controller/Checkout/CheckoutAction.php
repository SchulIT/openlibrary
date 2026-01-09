<?php

namespace App\Controller\Checkout;

use App\Checkout\BulkCheckoutRequest;
use App\Checkout\CheckoutManager;
use App\Form\BulkCheckoutType;
use App\Security\Voter\CheckoutVoter;
use App\Settings\CheckoutSettings;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

class CheckoutAction extends AbstractController {

    #[Route('/checkout/add', name: 'checkout')]
    public function __invoke(
        CheckoutManager $checkoutManager,
        CheckoutSettings $checkoutSettings,
        Request $request,
        ClockInterface $clock
    ): Response {
        $this->denyAccessUnlessGranted(CheckoutVoter::CHECKOUT_ANY);

        $bulkCheckoutRequest = new BulkCheckoutRequest();
        $bulkCheckoutRequest->expectedEnd = DateTime::createFromImmutable($clock->now()->modify(sprintf('+%d days', $checkoutSettings->defaultCheckoutDurationInDays)));

        $form = $this->createForm(BulkCheckoutType::class, $bulkCheckoutRequest);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $checkoutManager->bulkCheckout($bulkCheckoutRequest);

                return $this->redirectToRoute('show_borrower', [
                    'uuid' => $bulkCheckoutRequest->borrower->getUuid()
                ]);
            } catch (Throwable $e) {

            }
        }

        return $this->render('checkout/checkout.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
