<?php

namespace App\Controller\Label;

use App\Entity\LabelTemplate;
use App\Repository\LabelRepositoryInterface;
use App\Security\Voter\LabelVoter;
use SchulIT\CommonBundle\Form\ConfirmType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RemoveAction extends AbstractController {

    public function __construct(private readonly LabelRepositoryInterface $repository) {

    }

    #[Route('/labels/{uuid}/remove', name: 'remove_label')]
    public function __invoke(#[MapEntity(mapping: ['uuid' => 'uuid'])] LabelTemplate $label, Request $request, LabelRepositoryInterface $labelRepository): Response {
        $this->denyAccessUnlessGranted(LabelVoter::DELETE, $label);

        $form = $this->createForm(ConfirmType::class, [], [
            'message' => 'labels.remove.confirm',
            'message_parameters' => [
                'name' => $label->getName()
            ]
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->repository->remove($label);
            $this->addFlash('success', 'labels.remove.success');

            return $this->redirectToRoute('labels');
        }

        return $this->render('labels/remove.html.twig', [
            'label' => $label,
            'form' => $form->createView()
        ]);
    }
}
