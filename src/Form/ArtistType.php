<?php

// src/Form/ArtistType.php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('description', TextareaType::class)
            ->add('priority', IntegerType::class)
            ->add('style', TextType::class)
            ->add('origin', TextType::class, ['label' => 'Origine'])
            ->add('youtubeLink', TextType::class, ['label' => 'Lien Youtube'])
            ->add('image', ImageType::class, ['label' => 'Image'])
            ->add('submit', SubmitType::class, ['label' => 'Valider']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
            'validation_groups' => ['artistImage']
        ]);
    }
}
