<?php

namespace App\Label;

use App\Entity\Book;
use App\Entity\LabelTemplate;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class DownloadLabelsRequestType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('books', EntityType::class, [
                'label' => 'labels.download.books.label',
                'class' => Book::class,
                'multiple' => true,
                'help' => 'labels.download.books.help',
                'attr' => [
                    'data-choice' => 'true'
                ]
            ])
            ->add('printObjective', EnumType::class, [
                'class' => PrintObjective::class,
                'label' => 'labels.download.objective.label',
                'help' => 'labels.download.objective.help'
            ])
            ->add('template', EntityType::class, [
                'label' => 'labels.download.template',
                'class' => LabelTemplate::class,
                'choice_label' => fn(LabelTemplate $template) => $template->getName(),
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->createQueryBuilder('l')
                        ->addOrderBy('l.name', 'asc');
                },
                'attr' => [
                    'data-choice' => 'true'
                ]
            ])
            ->add('offset', IntegerType::class, [
                'label' => 'labels.download.offset.label',
                'help' => 'labels.download.offset.help',
            ]);
    }
}
