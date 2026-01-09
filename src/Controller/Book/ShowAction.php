<?php

namespace App\Controller\Book;

use App\Entity\Book;
use App\Security\Voter\BookVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShowAction extends AbstractController {

    #[Route('/books/{uuid}', name: 'show_book')]
    public function __invoke(
        #[MapEntity(mapping: ['uuid' => 'uuid'])] Book $book,
        ClockInterface $clock
    ): Response {
        $this->denyAccessUnlessGranted(BookVoter::SHOW, $book);

        return $this->render('books/show.html.twig', [
            'book' => $book,
            'today' => $clock->now()->setTime(0,0,0)
        ]);
    }
}
