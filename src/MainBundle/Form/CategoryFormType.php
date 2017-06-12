<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use MainBundle\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategoryFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

      
        $builder->add('nombre', TextType::class, array(
            'label' => 'Nombre',
            'attr' => array('class' => 'form-control')
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Category::class,
        ));
    }

}
