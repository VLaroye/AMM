<?php

// src/Form/EventType.php

namespace App\Form;

use App\Entity\{Event, EventCategory};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\{FormBuilderInterface, AbstractType};
use Symfony\Component\Form\Extension\Core\Type\{TimeType, UrlType, SubmitType, TextType, IntegerType, DateType};
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('location', TextType::class, [
                'label' => 'Lieux',
                'required' => false,
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix',
                'required' => false,
            ])
            ->add('facebookLink', UrlType::class, [
                'label' => 'Lien évènement Facebook',
                'required' => false,
            ])
            ->add('date', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'required' => false,
                ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => EventCategory::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisir une catégorie',
                'required' => false,
            ])
            ->add('beginningTime', TimeType::class, [
                'label' => 'Heure de début',
                'required' => false,
            ])
            ->add('endingTime', TimeType::class, [
                'label' => 'Heure de fin',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, ['label' => 'Valider']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
