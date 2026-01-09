<?php

namespace App\Form;

use App\Book\Shelfmark\Generator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class CategoryType extends AbstractType {
    public function __construct(
        private readonly Generator $generator,
        private readonly TranslatorInterface $translator
    ) {

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $choices = [ ];

        foreach($this->generator->getStrategies() as $strategy) {
            $choices[$this->translator->trans($strategy->getLabelTranslationKey())] = get_class($strategy);
        }

        $builder
            ->add('abbreviation', TextType::class, [
                'label' => 'categories.abbreviation'
            ])
            ->add('name', TextType::class, [
                'label' => 'categories.name'
            ])
            ->add('shelfmarkGenerator', ChoiceType::class, [
                'label' => 'categories.shelfmark_generator.label',
                'help' => 'categories.shelfmark_generator.help',
                'choices' => $choices,
                'placeholder' => 'categories.shelfmark_generator.choose'
            ])
            ->add('shelfmarkGeneratorParameter', TextType::class, [
                'label' => 'categories.shelfmark_generator.parameter.label',
                'help' => 'categories.shelfmark_generator.parameter.help',
                'required' => false
            ]);
    }
}
