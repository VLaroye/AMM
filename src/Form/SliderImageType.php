<?php

// src/Form/SliderImageType.php

namespace App\Form;

use App\Entity\SliderImage;
use Symfony\Component\Form\{FormBuilderInterface, AbstractType};
use Symfony\Component\Form\Extension\Core\Type\{TextType, FileType, SubmitType};
use Symfony\Component\OptionsResolver\OptionsResolver;

class SliderImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class)
            ->add('alt', TextType::class, ['label' => 'Texte alternatif'])
            ->add('submit', SubmitType::class, ['label' => 'Valider']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SliderImage::class,
        ]);
    }
}
