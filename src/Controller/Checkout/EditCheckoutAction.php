<?php

namespace App\Controller\Checkout;

use App\Entity\Checkout;
use App\Form\CheckoutType;
use App\Repository\CheckoutRepositoryInterface;
use App\Security\Voter\CheckoutVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EditCheckoutAction extends AbstractController {

    #[Route('/checkout/{uuid}/edit', name: 'edit_checkout')]
    public function __invoke(
        #[MapEntity(mapping: ['uuid' => 'uuid'])] Checkout $checkout,
        Request $request,
        CheckoutRepositoryInterface $checkoutRepository
    ): Response {
        $this->denyAccessUnlessGranted(CheckoutVoter::Edit, $checkout);

        $form = $this->createForm(CheckoutType::class, $checkout);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $checkoutRepository->persist($checkout);
            $this->addFlash('success', 'checkouts.edit.success');

            return $this->redirectToRoute('checkouts');
        }

        return $this->render('checkout/edit.html.twig', [
            'form' => $form->createView(),
            'checkout' => $checkout
        ]);
    }
}
