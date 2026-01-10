<?php

namespace App\Controller\Label;

use App\Repository\LabelRepositoryInterface;
use App\Repository\PaginationQuery;
use App\Security\Voter\LabelVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class IndexAction extends AbstractController {
    #[Route('/labels', name: 'labels')]
    public function __invoke(
        LabelRepositoryInterface $labelRepository,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $limit = 25,
    ): Response {
        $this->denyAccessUnlessGranted(LabelVoter::LIST);

        return $this->render('labels/index.html.twig', [
            'labels' => $labelRepository->find(new PaginationQuery(page: $page, limit: $limit))
        ]);
    }
}
