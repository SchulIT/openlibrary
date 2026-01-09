<?php

namespace App\Controller\Borrower;

use App\Entity\BorrowerType;
use App\Repository\BorrowerRepositoryInterface;
use App\Repository\PaginationQuery;
use App\Security\Voter\BorrowerVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class IndexAction extends AbstractController {

    #[Route('/borrowers', name: 'admin_borrowers')]
    public function __invoke(
        BorrowerRepositoryInterface $borrowerRepository,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter(filter: FILTER_DEFAULT, flags: FILTER_FLAG_EMPTY_STRING_NULL | FILTER_NULL_ON_FAILURE)] string|null $query = null,
        #[MapQueryParameter(filter: FILTER_DEFAULT, flags: FILTER_FLAG_EMPTY_STRING_NULL | FILTER_NULL_ON_FAILURE)] string|null $grade = null,
        #[MapQueryParameter(name: 'type', flags: FILTER_NULL_ON_FAILURE )] BorrowerType|null $borrowerType = null
    ): Response {
        $this->denyAccessUnlessGranted(BorrowerVoter::LIST);

        $borrowers = $borrowerRepository->find(new PaginationQuery(page: $page), $borrowerType !== null ? [ $borrowerType ] : BorrowerType::cases(), $grade, $query);
        $grades = $borrowerRepository->findAllGrades();

        return $this->render('borrowers/index.html.twig', [
            'borrowers' => $borrowers,
            'query' => $query,
            'selectedGrade' => $grade,
            'selectedType' => $borrowerType,
            'types' => BorrowerType::cases(),
            'grades' => $grades
        ]);
    }
}
