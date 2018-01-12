<?php

// src/Form/ArtistType.php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\{FormBuilderInterface, AbstractType};
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType, IntegerType, SubmitType};

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('description', TextType::class)
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
        ]);
    }
}
