<?php

// src/Form/EventCategoryType.php

namespace App\Form;

use App\Entity\EventCategory;
use Symfony\Component\Form\{FormBuilderInterface, AbstractType};
use Symfony\Component\Form\Extension\Core\Type\{TextType, SubmitType};
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la catégorie',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EventCategory::class,
        ]);
    }
}
