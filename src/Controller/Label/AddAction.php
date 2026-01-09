<?php

namespace App\Controller\Label;

use App\Entity\LabelTemplate;
use App\Form\LabelTemplateType;
use App\Repository\LabelRepositoryInterface;
use App\Security\Voter\LabelVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AddAction extends AbstractController {

    #[Route('/labels/add', name: 'add_label')]
    public function __invoke(Request $request, LabelRepositoryInterface $labelRepository): Response {
        $this->denyAccessUnlessGranted(LabelVoter::NEW);

        $label = new LabelTemplate();
        $form = $this->createForm(LabelTemplateType::class, $label);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $labelRepository->persist($label);
            $this->addFlash('success', 'labels.add.success');

            return $this->redirectToRoute('labels');
        }

        return $this->render('labels/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
