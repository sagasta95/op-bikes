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
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use MainBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use FOS\UserBundle\Util\LegacyFormHelper;

class UserFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $constraintsOptions = array(
            'message' => 'fos_user.current_password.invalid',
        );

        $builder->add('username', TextType::class, array('label' => 'Username:', 'translation_domain' => 'FOSUserBundle', 'attr' => array('class' => 'form-control')));
        $builder->add('email', EmailType::class, array('label' => 'E-mail:', 'translation_domain' => 'FOSUserBundle', 'attr' => array('class' => 'form-control')));
        $builder->add('roles', ChoiceType::class, array(
            'choices' => array(
                'Usuario' => '',
                'Administrador' => 'ROLE_SUPER_ADMIN',
            ),
            'mapped' => false,
            'required' => false,
            'label' => 'Rol:',
            'expanded' => false,
            'attr' => array('class' => 'form-control')
        ));
        $builder->add('enabled', CheckboxType::class, array(
            'label' => 'Activado:',
            'required' => false,
            'attr' => array('class' => 'checkbox')
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }

}
