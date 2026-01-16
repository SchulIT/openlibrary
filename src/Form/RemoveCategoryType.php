<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Override;
use SchulIT\CommonBundle\Form\ConfirmType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class RemoveCategoryType extends ConfirmType {

    public function configureOptions(OptionsResolver $resolver): void {
        parent::configureOptions($resolver);
        $resolver->setDefault('category', null);
        $resolver->isRequired('category');
    }

    #[Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        parent::buildForm($builder, $options);

        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'label' => 'categories.remove.category.label',
                'help' => 'categories.remove.category.help',
                'placeholder' => 'filter.choose.category',
                'constraints' => [
                    new NotNull()
                ],
                'query_builder' => function (EntityRepository $er) use($options): QueryBuilder {
                    return $er->createQueryBuilder('c')
                        ->where('c.id != :id')
                        ->setParameter('id', $options['category']->getId())
                        ->orderBy('c.name', 'ASC');
                },
            ]);
    }
}
