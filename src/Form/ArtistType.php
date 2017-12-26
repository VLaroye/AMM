<?php

// src/Form/ArtistType.php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Nom'))
            ->add('description', TextType::class)
            ->add('style', TextType::class)
            ->add('origin', TextType::class, array('label' => 'Origine'))
            ->add('youtubeLink', TextType::class, array('label' => "Lien Youtube"))
            ->add('image', ImageType::class)
            ->add('submit', SubmitType::class, array('label' => "Ajouter l'artiste"));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Artist::class,
        ));
    }
}