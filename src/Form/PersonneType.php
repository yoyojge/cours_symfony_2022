<?php

namespace App\Form;

use App\Entity\Personne;
use App\Entity\Sport;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("nom", TextType::class)
            ->add("prenom", TextType::class)
            ->add("adresse", AdresseType::class)
            ->add(
                "sports",
                EntityType::class, //une liste de selection
                [
                    'class' => Sport::class,
                    'choice_label' => 'nom', //<option>{{nom}}</option>
                    'query_builder' => function (EntityRepository $repo) {
                        return $repo->createQueryBuilder('s');
                    },
                    'label' => "Sports préférés",
                    'multiple' => true
                ]
            )
            // ->add(
            //     "sports",
            //     CollectionType::class,
            //     [
            //         'entry_type' => SportType::class,
            //         'entry_options' => ['label' => false],
            //         'allow_add' => true,
            //         'allow_delete' => true
            //     ]
            // )
            ->add("creer", SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personne::class,
        ]);
    }
}
