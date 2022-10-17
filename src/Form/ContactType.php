<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('last_name', TextType::class, [
                "label" => "NOM :",
                "required" => true
            ])
            ->add('first_name', TextType::class, [
                "label" => "Prénom :",
                "required" => true
            ])
            ->add('email', EmailType::class, [
                "label" => "Email :",
                "required" => true
            ])
            ->add("subject", ChoiceType::class, [
                "label" => "Sujet :",
                "required" => true,
                "choices" => [
                    "test" => "n1code7@outlook.fr"
                    // "Général" => "contact@villeparisisbadminton77.fr",
                    // "Adhésions / Renouvellement" => "adhesion@villeparisisbadminton77.fr",
                    // "Tournois Badtour" => "tournois@villeparisisbadminton77.fr",
                    // "Jeunes" => "jeunes@villeparisisbadminton77.fr",
                    // "Compétiteurs" => "competitions@villeparisisbadminton77.fr",
                    // "Loisirs" => "loisirs@villeparisisbadminton77.fr"
                ]
            ])
            ->add('message', TextareaType::class, [
                "label" => "Message :",
                "required" => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
