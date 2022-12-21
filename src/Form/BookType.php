<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\BookCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author', null, array(
                    'attr' => array(
                        'placeholder' => 'saisir le nom de l auteur',
                    ))
            )

           ->add('title', null, array(
                   'attr' => array(
                       'placeholder' => 'saisir le titre du livre',
                   ))
           )
            ->add('description', null, array(
                    'attr' => array(
                        'placeholder' => 'saisir la description du livre',
                    ))
            )

            ->add('cat', EntityType::class,[
                'class'=>BookCategory::class,
               'empty_data'  => null,
                'choice_label'=>'catId',
                'required'    => false,



            ])
            ->add('filePath', FileType::class, array('data_class' => null))

            ->add('imgUrl', FileType::class, array('data_class' => null))

            // ...


         //   ->add('ajouter',SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);



    }
}
