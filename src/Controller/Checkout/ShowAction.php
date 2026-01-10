<?php

namespace App\Controller\Checkout;

use App\Entity\Checkout;
use App\Security\Voter\CheckoutVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShowAction extends AbstractController {

    #[Route('/checkout/{uuid}', name: 'show_checkout')]
    public function __invoke(
        #[MapEntity(mapping: [ 'uuid' => 'uuid'])] Checkout $checkout
    ): Response {
        $this->denyAccessUnlessGranted(CheckoutVoter::SHOW, $checkout);

        return $this->render('checkout/show.html.twig', [
            'checkout' => $checkout
        ]);
    }
}
