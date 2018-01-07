<?php

// src/Form/ArtistType.php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

/**
 * Class ArtistType
 */
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
            ->add('imageFile', FileType::class, ['label' => 'Fichier image'])
            ->add('imageAlt', TextType::class, ['label' => 'Image Alt'])
            ->add('submit', SubmitType::class, ['label' => 'Valider']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
        ]);
    }
}
