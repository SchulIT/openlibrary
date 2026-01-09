<?php

namespace App\Controller\Borrower;

use App\Entity\Borrower;
use App\Security\Voter\BorrowerVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShowAction extends AbstractController {

    #[Route('/borrowers/{uuid}', name: 'show_borrower')]
    public function __invoke(
        #[MapEntity(mapping: [ 'uuid' => 'uuid' ])] Borrower $borrower,
        ClockInterface $clock
    ): Response {
        $this->denyAccessUnlessGranted(BorrowerVoter::SHOW, $borrower);

        return $this->render('borrowers/show.html.twig', [
            'borrower' => $borrower,
            'today' => $clock->now()->setTime(0,0,0)
        ]);
    }
}
