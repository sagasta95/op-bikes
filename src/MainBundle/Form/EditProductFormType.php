<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use MainBundle\Entity\Product;
use MainBundle\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EditProductFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('nombre', TextType::class, array(
                    'label' => 'Nombre:',
                    'attr' => array('class' => 'form-control')
                ))
                ->add('descripcion', TextareaType::class, array(
                    'label' => 'Descripción:',
                    'attr' => array(
                        'class' => 'form-control',
                        'style' => 'resize: vertical;'
                    )
                ))
                ->add('category', EntityType::class, array(
                    'label' => 'Categoría:',
                    'class' => 'MainBundle:Category',
                    'attr' => array('class' => 'form-control')
                ))
                ->add('precio', NumberType::class, array(
                    'label' => 'Precio:',
                    'attr' => array(
                        'class' => 'form-control',
                        'type' => 'number'
                    )
                ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Product::class,
        ));
    }

}
