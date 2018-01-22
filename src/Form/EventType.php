<?php

// src/Form/EventType.php

namespace App\Form;

use App\Entity\Event;
use App\Entity\EventCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('facebookLink', UrlType::class, [
                'label' => 'Lien évènement Facebook',
                'required' => false,
            ])
            ->add('beginningDateTime', DateTimeType::class, [
                'label' => 'Date et heure de début',
                ])
            ->add('endingDateTime', DateTimeType::class, [
                'label' => 'Date et heure de fin',
                'required' => false,
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => EventCategory::class,
                'choice_label' => 'name',
            ])
            ->add('image', ImageType::class, ['label' => 'Image'])
            ->add('submit', SubmitType::class, ['label' => 'Valider']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'validation_groups' => ['eventImage']
        ]);
    }
}
