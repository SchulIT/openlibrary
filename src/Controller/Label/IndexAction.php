<?php

namespace App\Controller\Label;

use App\Repository\LabelRepositoryInterface;
use App\Security\Voter\LabelVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexAction extends AbstractController {
    #[Route('/labels', name: 'labels')]
    public function __invoke(LabelRepositoryInterface $labelRepository): Response {
        $this->denyAccessUnlessGranted(LabelVoter::LIST);

        return $this->render('labels/index.html.twig', [
            'labels' => $labelRepository->findAll()
        ]);
    }
}
