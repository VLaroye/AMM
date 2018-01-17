<?php

// src/Form/UpdateSliderImageType.php

namespace App\Form;

use App\Entity\SliderImage;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateSliderImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alt', TextType::class, ['label' => 'Texte alternatif'])
            ->add('position', ChoiceType::class, [
                'choices' => $options['positions'],
                'choice_label' => function ($value, $key, $index) {
                    return $value;
                },
            ])
            ->add('submit', SubmitType::class, ['label' => 'Valider']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SliderImage::class,
            'positions' => null
        ]);
    }
}
