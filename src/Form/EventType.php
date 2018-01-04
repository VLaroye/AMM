<?php

// src/Form/EventType.php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Nom'))
            ->add('location', TextType::class, array('label' => 'Lieux'))
            ->add('price', IntegerType::class, array('label' => 'Prix'))
            ->add('facebookLink', UrlType::class, array('label' => 'Lien évènement Facebook'))
            ->add('date', DateType::class, array(
                'label' => 'Date',
                'widget' => 'single_text'
                ))
            ->add('beginningTime', TimeType::class, array('label' => 'Heure de début'))
            ->add('endingTime', TimeType::class, array('label' => 'Heure de fin'))
            ->add('submit', SubmitType::class, array('label' => "Valider"));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Event::class,
        ));
    }
}
