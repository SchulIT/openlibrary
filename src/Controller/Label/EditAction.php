<?php

namespace App\Controller\Label;

use App\Entity\LabelTemplate;
use App\Form\LabelTemplateType;
use App\Repository\LabelRepositoryInterface;
use App\Security\Voter\LabelVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EditAction extends AbstractController {

    #[Route('/labels/{uuid}/edit', name: 'edit_label')]
    public function __invoke(#[MapEntity(mapping: ['uuid' => 'uuid'])] LabelTemplate $label, Request $request, LabelRepositoryInterface $labelRepository): Response {
        $this->denyAccessUnlessGranted(LabelVoter::EDIT, $label);

        $form = $this->createForm(LabelTemplateType::class, $label);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $labelRepository->persist($label);
            $this->addFlash('success', 'labels.edit.success');

            return $this->redirectToRoute('labels');
        }

        return $this->render('labels/edit.html.twig', [
            'form' => $form->createView(),
            'label' => $label
        ]);
    }
}
