<?php

namespace App\Controller\Checkout;

use App\Http\ValueResolver\MapDateTimeQueryParameter;
use App\Repository\CheckoutRepositoryInterface;
use App\Repository\PaginationQuery;
use App\Security\Voter\CheckoutVoter;
use DateTime;
use DateTimeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapDateTime;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class IndexAction extends AbstractController {

    #[Route('/checkout', name: 'checkouts')]
    public function __invoke(
        CheckoutRepositoryInterface $checkoutRepository,
        ClockInterface $clock,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $limit = 25,
        #[MapQueryParameter(filter: FILTER_DEFAULT, flags: FILTER_FLAG_EMPTY_STRING_NULL | FILTER_NULL_ON_FAILURE)] string|null $query = null,
        #[MapDateTimeQueryParameter] DateTime|null $start = null,
        #[MapDateTimeQueryParameter] DateTime|null $end = null,
        #[MapQueryParameter(name: 'active')] bool $onlyActive = false,
        #[MapQueryParameter(name: 'overdue')] bool $onlyOverdue = false
    ): Response {
        $this->denyAccessUnlessGranted(CheckoutVoter::LIST);
        $checkouts = $checkoutRepository->find(new PaginationQuery(page: $page, limit: $limit), start: $start, end: $end, onlyActive: $onlyActive, onlyOverdue: $onlyOverdue, query: $query);

        return $this->render('checkout/index.html.twig', [
            'checkouts' => $checkouts,
            'query' => $query,
            'start' => $start,
            'end' => $end,
            'onlyActive' => $onlyActive,
            'onlyOverdue' => $onlyOverdue,
            'today' => $clock->now()->setTime(0,0,0)
        ]);
    }
}
