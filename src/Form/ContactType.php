<?php

// src/Form/ContactType.php

namespace App\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom',
                    ],
                ])
            ->add('secondName', TextType::class, [
                'label' => 'Prenom',
                'attr' => [
                    'placeholder' => 'Prenom',
                    ],
                ])
            ->add('mail', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Email',
                    ],
                ])
            ->add('subject', ChoiceType::class, [
                'label' => 'Sujet',
                'choices' => [
                    'Demande générale ?' => 'Demande générale',
                    'Proposition de groupe ? Thibault te répondra !' => 'Proposition de groupe',
                    'Question à propos de l\'asso ?' => 'Question générale',
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Ton message',
                'attr' => [
                    'placeholder' => 'Message',
                ],
            ])
            ->add('submit', SubmitType::class, ['label' => 'Envoyer !']);
    }
}
