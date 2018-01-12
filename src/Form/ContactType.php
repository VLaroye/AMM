<?php

// src/Form/ContactType.php

namespace App\Form;

use Symfony\Component\Form\{FormBuilderInterface, AbstractType};
use Symfony\Component\Form\Extension\Core\Type\{EmailType, TextareaType, SubmitType, TextType, ChoiceType};

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'Nom'])
            ->add('secondName', TextType::class, ['label' => 'Prenom'])
            ->add('mail', EmailType::class, ['label' => 'Email'])
            ->add('subject', ChoiceType::class, [
                'label' => 'Sujet',
                'choices' => [
                    'Demande générale ?' => 'Demande générale',
                    'Proposition de groupe ? Thibault te répondra !' => 'Proposition de groupe',
                    'Question à propos de l\'asso ?' => 'Question générale'
                ]
            ])
            ->add('message', TextareaType::class, ['label' => 'Ton message'])
            ->add('submit', SubmitType::class, ['label' => 'Envoyer !']);
    }
}
