<?php

// src/Form/ImageType.php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, [
                'label' => 'Fichier',
                'required' => false
            ])
            ->add('alt', TextType::class, [
                'label' => 'Texte alternatif',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
            'validation_groups' => ['eventImage', 'eventCoverImage'],
        ]);
    }
}
