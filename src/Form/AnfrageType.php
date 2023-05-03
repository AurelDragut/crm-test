<?php

namespace App\Form;

use App\Entity\Artikel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class AnfrageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $i=0;
        foreach ($options['Artikel'] as $artikel) {
            $choices = [];
            for ($j=1;$j<=$artikel->getMenge();$j++) {
                $choices[$j.' von '.$artikel->getMenge()] = $j;
            }

            $artikelOptions = [];
            $artikelOptions['data'] = $artikel;
            $artikelOptions['data']->choices = $choices; 
            $artikelOptions['data']->Gruende = $options['Gruende'];
            $artikelOptions['data_class'] = Artikel::class;

            $options['Indexes'] = $i;

            $builder->add('Artikel-'.$i+1, ArtikelType::class, $options);

            /*$builder->add(
                $builder->create('Artikel-'.$i+1, FormType::class, ['form_attr' => true, 'by_reference' => false, 'attr' => ['id' => 'produkt-'.$i]])
                    ->add('artikelnummer', HiddenType::class, ['attr' => ['value' => $artikel->getArtikelnummer()]])
                    ->add('bezeichnung', TextType::class, ['row_attr' => ['class' => 'col-md-11'], 'attr' => ['value' => $artikel->getBezeichnung()]])
                    ->add('entfernen', ButtonType::class, ['label' => 'X', 'row_attr' => ['class' => 'col-md-1'], 'attr' => ['class' => 'btn btn-danger btn-lg', 'onclick' => "document.getElementById('anfrage_Artikel-'+".($i+1).").parentNode.remove();"]])
                    ->add('menge', ChoiceType::class, ['row_attr' => ['class' => 'col-md-6 pt-1 pb-1'], 'choices'  => $choices,'attr' => ['value' => $artikel->getMenge()]])
                    ->add('grund', ChoiceType::class, ['row_attr' => ['class' => 'col-md-6 pt-1 pb-1'], 'choices' => $options['Gruende']])
                    ->add('beschreibung', TextareaType::class, ['required' => true, 'row_attr' => ['class' => 'col-md-12']]));
            $i++;*/
        }
        $builder
            ->add('Rechnungsnummer', HiddenType::class)
            ->add('Anrede', TextType::class, ['label' => 'Anrede', 'row_attr' => ['class' => 'col-md-4']])
            ->add('Vorname', TextType::class, ['label' => 'Vorname', 'row_attr' => ['class' => 'col-md-4']])
            ->add('Nachname', TextType::class, ['label' => 'Nachname', 'row_attr' => ['class' => 'col-md-4']])
            ->add('Strasse', TextType::class, ['label' => 'Straße', 'row_attr' => ['class' => 'col-md-8']])
            ->add('PLZ', TextType::class, ['label' => 'PLZ', 'row_attr' => ['class' => 'col-md-4']])
            ->add('Stadt', TextType::class, ['label' => 'Stadt', 'row_attr' => ['class' => 'col-md-6']])
            ->add('Land', TextType::class, ['label' => 'Land', 'row_attr' => ['class' => 'col-md-6']])
            ->add('Telefon', TextType::class, ['label' => 'Telefon', 'row_attr' => ['class' => 'col-md-6']])
            ->add('Email', TextType::class, ['label' => 'Email', 'row_attr' => ['class' => 'col-md-6'], 'required' => true, 'constraints' => [new NotBlank(), new Email()]])
            ->add('Bild_1', FileType::class, ['mapped' => false, 'required' => false, 'attr' => ['accept' => "image/*"], 'label' => 'Bild #1', 'row_attr' => ['class' => 'col-md-12'], 'data_class' => null])
            ->add('Bild_2', FileType::class, ['mapped' => false, 'required' => false, 'attr' => ['accept' => "image/*"], 'label' => 'Bild #2', 'row_attr' => ['class' => 'col-md-12'], 'data_class' => null])
            ->add('Bild_3', FileType::class, ['mapped' => false, 'required' => false, 'attr' => ['accept' => "image/*"], 'label' => 'Bild #3', 'row_attr' => ['class' => 'col-md-12'], 'data_class' => null])
            ->add('Bild_4', FileType::class, ['mapped' => false, 'required' => false, 'attr' => ['accept' => "image/*"], 'label' => 'Bild #4', 'row_attr' => ['class' => 'col-md-12'], 'data_class' => null])
            ->add('Bild_5', FileType::class, ['mapped' => false, 'required' => false, 'attr' => ['accept' => "image/*"], 'label' => 'Bild #5', 'row_attr' => ['class' => 'col-md-12'], 'data_class' => null])
            ->add('Datenschutzbestimmungen', CheckboxType::class, ['attr' => ['style' => 'visibility: hidden'], 'row_attr' => ['class' => 'col-md-12']])
            ->add('Abschicken', SubmitType::class, ['attr' => ['class' => 'btn-success form-control'], 'row_attr' => ['class' => 'col-md-6']])
            ->add('Abbrechen', ResetType::class, ['attr' => ['class' => 'btn-danger form-control'], 'row_attr' => ['class' => 'col-md-6']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // ...,
            'require_due_date' => false,
            'Artikel' => array(),
            'Gruende' => array(),
            'Indexes' => 'integer'
        ]);

        // you can also define the allowed types, allowed values and
        // any other feature supported by the OptionsResolver component
        $resolver->setAllowedTypes('require_due_date', 'bool');
        $resolver->setAllowedTypes('Artikel', 'array');
        $resolver->setAllowedTypes('Gruende', 'array');
        $resolver->setAllowedTypes('Indexes', 'integer');
    }
}
