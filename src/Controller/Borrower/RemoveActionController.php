<?php

declare(strict_types=1);

namespace App\Controller\Borrower;

use App\Entity\Borrower;
use App\Repository\BorrowerRepositoryInterface;
use App\Security\Voter\BorrowerVoter;
use SchulIT\CommonBundle\Form\ConfirmType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RemoveActionController extends AbstractController {
    #[Route('/borrowers/{uuid}/remove', name: 'remove_borrower')]
    public function __invoke(
        #[MapEntity(mapping: ['uuid' => 'uuid'])] Borrower $borrower,
        Request $request,
        BorrowerRepositoryInterface $borrowerRepository,
    ): Response {
        $this->denyAccessUnlessGranted(BorrowerVoter::DELETE, $borrower);

        $form = $this->createForm(ConfirmType::class, [], [
            'message' => 'borrowers.remove.confirm',
            'message_parameters' => [
                'firstname' => $borrower->getFirstname(),
                'lastname' => $borrower->getLastname(),
                'barcodeId' => $borrower->getBarcodeId()
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $borrowerRepository->remove($borrower);
            $this->addFlash('success', 'borrowers.remove.success');
            return $this->redirectToRoute('admin_borrowers');
        }

        return $this->render('borrowers/remove.html.twig', [
            'borrower' => $borrower,
            'form' => $form->createView()
        ]);
    }
}
