<?php

namespace App\Form;

use App\Entity\Artikel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ArtikelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $index = $options['Indexes'];

        $builder
            ->add('artikelnummer', HiddenType::class, ['attr' => ['value' => $options['Artikel'][$index]->getArtikelnummer()]])
            ->add('bezeichnung', TextType::class, ['label' => 'Name', 'row_attr' => ['class' => 'col-md-11'], 'attr' => ['value' => $options['Artikel'][$index]->getBezeichnung()]])
            ->add('entfernen', ButtonType::class, ['label' => 'X', 'row_attr' => ['class' => 'col-md-1'], 'attr' => ['class' => 'btn btn-danger btn-lg', 'onclick' => "this.parentNode.parentNode.parentNode.remove();"]])
            ->add('menge', ChoiceType::class, ['row_attr' => ['class' => 'col-md-6 pt-1 pb-1'], 'choices'  => $options['Artikel'][$index]->choices,'attr' => ['value' => $options['Artikel'][$index]->getMenge()]])
            ->add('grund', ChoiceType::class, ['row_attr' => ['class' => 'col-md-6 pt-1 pb-1'], 'choices' => $options['Artikel'][$index]->Gruende])
            ->add('beschreibung', TextareaType::class, ['row_attr' => ['class' => 'col-md-12']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artikel::class,
            'require_due_date' => false,
            'Artikel' => array(),
            'Gruende' => array(),
            'Indexes' => 'integer'
        ]);

        $resolver->setAllowedTypes('require_due_date', 'bool');
        $resolver->setAllowedTypes('Artikel', 'array');
        $resolver->setAllowedTypes('Gruende', 'array');
        $resolver->setAllowedTypes('Indexes', 'integer');
    }
}
