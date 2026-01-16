<?php

namespace App\Controller\Book;

use App\Entity\Book;
use App\Repository\BookRepositoryInterface;
use App\Security\Voter\BookVoter;
use SchulIT\CommonBundle\Form\ConfirmType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RemoveAction extends AbstractController {

    #[Route('/books/{uuid}/remove', name: 'remove_book')]
    public function __invoke(
        #[MapEntity(mapping: ['uuid' => 'uuid'])] Book $book,
        Request $request,
        BookRepositoryInterface $bookRepository
    ): Response {
        $this->denyAccessUnlessGranted(BookVoter::DELETE, $book);

        $form = $this->createForm(ConfirmType::class, [], [
            'message' => 'books.remove.confirm',
            'message_parameters' => [
                'title' => $book->getTitle(),
                'barcodeId' => $book->getBarcodeId()
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookRepository->remove($book);
            $this->addFlash('success', 'books.remove.success');
            return $this->redirectToRoute('admin_books');
        }

        return $this->render('books/remove.html.twig', [
            'form' => $form->createView(),
            'book' => $book
        ]);
    }
}
