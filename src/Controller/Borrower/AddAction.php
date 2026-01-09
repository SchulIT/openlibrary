<?php

namespace App\Controller\Borrower;

use App\Entity\Borrower;
use App\Form\BorrowerType;
use App\Repository\BorrowerRepositoryInterface;
use App\Security\Voter\BorrowerVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AddAction extends AbstractController {

    #[Route('/borrowers/add', name: 'add_borrower')]
    public function __invoke(
        Request $request,
        BorrowerRepositoryInterface $borrowerRepository
    ): Response {
        $this->denyAccessUnlessGranted(BorrowerVoter::NEW);

        $borrower = new Borrower();
        $form = $this->createForm(BorrowerType::class, $borrower);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $borrowerRepository->persist($borrower);

            $this->addFlash('success', 'borrowers.add.success');
            return $this->redirectToRoute('admin_borrowers');
        }

        return $this->render('borrowers/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
