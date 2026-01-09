<?php

namespace App\Controller\Book;

use App\Entity\Book;
use App\Form\BookType;
use App\Helper\BarcodeIdHelper;
use App\Repository\BookRepositoryInterface;
use App\Security\Voter\BookVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EditAction extends AbstractController {

    #[Route('/books/{uuid}/edit', name: 'admin_books_edit')]
    public function __invoke(
        #[MapEntity(mapping: ['uuid' => 'uuid'])] Book $book,
        Request $request,
        BookRepositoryInterface $bookRepository
    ): Response {
        $this->denyAccessUnlessGranted(BookVoter::EDIT, $book);

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookRepository->persist($book);

            $this->addFlash('success', 'books.edit.success');
            return $this->redirectToRoute('admin_books');
        }

        return $this->render('books/edit.html.twig', [
            'form' => $form->createView(),
            'book' => $book
        ]);
    }
}
