<?php

namespace App\Form;

use App\Entity\Rechnung;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechnungType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('BeNr')
            ->add('Rechnungsnummer')
            ->add('Webshop')
            ->add('Plattform')
            ->add('Anrede')
            ->add('Vorname')
            ->add('Nachname')
            ->add('Strasse')
            ->add('PLZ')
            ->add('Ort')
            ->add('Land')
            ->add('Tel')
            ->add('Email')
            ->add('KdNr')
            ->add('auftrag')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rechnung::class,
        ]);
    }
}
