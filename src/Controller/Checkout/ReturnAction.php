<?php

declare(strict_types=1);

namespace App\Controller\Checkout;

use App\Checkout\BulkReturnRequest;
use App\Checkout\CheckoutManager;
use App\Form\BulkReturnType;
use App\Security\Voter\CheckoutVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReturnAction extends AbstractController {
    #[Route('/return', name: 'return')]
    public function __invoke(
        Request $request,
        CheckoutManager $checkoutManager
    ): Response {
        $this->denyAccessUnlessGranted(CheckoutVoter::CHECKOUT_ANY);

        $bulkReturnRequest = new BulkReturnRequest();
        $form = $this->createForm(BulkReturnType::class, $bulkReturnRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $checkoutManager->bulkReturn($bulkReturnRequest);

            $this->addFlash('success', 'returns.success');
            return $this->redirectToRoute('return');
        }

        return $this->render('checkout/return.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
