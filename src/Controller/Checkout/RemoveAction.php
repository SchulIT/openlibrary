<?php

namespace App\Controller\Checkout;

use App\Entity\Checkout;
use App\Form\CheckoutType;
use App\Repository\CheckoutRepositoryInterface;
use App\Security\Voter\CheckoutVoter;
use SchulIT\CommonBundle\Form\ConfirmType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RemoveAction extends AbstractController {

    #[Route('/checkout/{uuid}/remove', name: 'remove_checkout')]
    public function __invoke(
        #[MapEntity(mapping: ['uuid' => 'uuid'])] Checkout $checkout,
        CheckoutRepositoryInterface $checkoutRepository,
        Request $request,
        TranslatorInterface $translator
    ): Response {
        $this->denyAccessUnlessGranted(CheckoutVoter::DELETE, $checkout);

        $form = $this->createForm(ConfirmType::class, [], [
            'message' => 'checkouts.remove.confirm',
            'message_parameters' => [
                'title' => $checkout->getBook()?->getTitle(),
                'barcodeId' => $checkout->getBook()?->getBarcodeId(),
                'firstname' => $checkout->getBorrower()?->getFirstname(),
                'lastname' => $checkout->getBorrower()?->getLastname(),
                'date' => $checkout->getCreatedAt()->format($translator->trans('date.with_time'))
            ]
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $checkoutRepository->remove($checkout);
            $this->addFlash('success', 'checkouts.remove.success');

            return $this->redirectToRoute('checkouts');
        }

        return $this->render('checkout/remove.html.twig', [
            'form' => $form->createView(),
            'checkout' => $checkout,
        ]);
    }
}
