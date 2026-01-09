<?php

namespace App\Controller\Book;

use App\Entity\Book;
use App\Form\BookType;
use App\Helper\BarcodeIdHelper;
use App\Repository\BookRepositoryInterface;
use App\Security\Voter\BookVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AddAction extends AbstractController {

    #[Route('/books/add', name: 'admin_books_add')]
    public function __invoke(
        Request $request,
        BookRepositoryInterface $bookRepository,
        BarcodeIdHelper $barcodeIdHelper
    ): Response {
        $this->denyAccessUnlessGranted(BookVoter::NEW);

        $book = new Book();
        $book->setBarcodeId($barcodeIdHelper->getNextAvailableBarcodeId());
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookRepository->persist($book);

            $this->addFlash('success', 'books.add.success');
            return $this->redirectToRoute('admin_books');
        }

        return $this->render('books/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
