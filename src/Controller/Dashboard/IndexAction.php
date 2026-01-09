<?php

namespace App\Controller\Dashboard;

use App\Dashboard\DashboardStatistics;
use App\Entity\User;
use App\Repository\CheckoutRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class IndexAction extends AbstractController {
    #[Route('/dashboard', name: 'dashboard')]
    public function __invoke(
        CheckoutRepositoryInterface $checkoutRepository,
        DashboardStatistics $dashboardStatistics,
        #[CurrentUser] User $user
    ): Response {
        $borrowers = $user->getAssociatedBorrowers();
        $activeCheckouts = [ ];

        foreach($borrowers as $borrower) {
            $activeCheckouts[$borrower->getId()] = $checkoutRepository->findActiveByBorrower($borrower);
        }

        return $this->render('dashboard/index.html.twig', [
            'activeCheckouts' => $activeCheckouts,
            'borrowers' => $borrowers,
            'statistics' => $dashboardStatistics->getStatistics()
        ]);
    }
}
