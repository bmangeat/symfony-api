<?php

namespace App\Form;

use App\Dto\Request\ContactFormDto;use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'empty_data' => ''
            ])
            ->add('email', EmailType::class, [
                'required' => true
            ])
            ->add('service', ChoiceType::class, [
                'choices' => [
                    'Ressources humaines' => 'rh@contact.fr',
                    'Support' => 'si@contact.fr',
                    'Marketing' => 'marketing@contact.fr',
                ]
            ])
            ->add('message', TextareaType::class, [
                'empty_data' => ''
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactFormDto::class,
        ]);
    }
}
