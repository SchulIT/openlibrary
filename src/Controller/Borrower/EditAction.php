<?php

namespace App\Controller\Borrower;

use App\Entity\Borrower;
use App\Form\BorrowerType;
use App\Repository\BorrowerRepositoryInterface;
use App\Security\Voter\BorrowerVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EditAction extends AbstractController {

    #[Route('/borrowers/{uuid}/edit', name: 'edit_borrower')]
    public function __invoke(
        Request $request,
        BorrowerRepositoryInterface $borrowerRepository,
        #[MapEntity(mapping: ['uuid' => 'uuid'])] Borrower $borrower
    ): Response {
        $this->denyAccessUnlessGranted(BorrowerVoter::EDIT, $borrower);

        $form = $this->createForm(BorrowerType::class, $borrower);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $borrowerRepository->persist($borrower);

            $this->addFlash('success', 'borrowers.edit.success');
            return $this->redirectToRoute('show_borrower', [
                'uuid' => $borrower->getUuid()
            ]);
        }

        return $this->render('borrowers/edit.html.twig', [
            'form' => $form->createView(),
            'borrower' => $borrower
        ]);
    }
}
