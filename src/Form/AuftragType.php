<?php

namespace App\Form;

use App\Entity\Auftrag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuftragType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name')
            ->add('Beschreibung')
            ->add('Menge')
            ->add('bilds', CollectionType::class, array(
                'data_class' => null,
                'mapped' => false,
            ))
            ->add('Grund')
            ->add('Mitarbeiter')
            ->add('status')
            ->add('artikel')
            ->add('Rechnung')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Auftrag::class,
        ]);
    }
}
