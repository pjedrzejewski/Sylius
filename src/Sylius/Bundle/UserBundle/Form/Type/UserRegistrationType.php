<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserRegistrationType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', 'text', array(
                'label' => 'sylius.form.user.first_name',
            ))
            ->add('lastName', 'text', array(
                'label' => 'sylius.form.user.last_name',
            ))
            ->add('email', 'text', array(
                'label' => 'sylius.form.user.last_name',
            ))
            ->add('plainPassword', 'repeated', array(
                'type'          => 'password',
                'first_options' => array('label' => 'sylius.form.user.password'),
                'second_options' => array('label' => 'sylius.form.user.password_confirmation'),
                'invalid_message' => 'sylius.form.user.password.mismatch',
            ))
        ;
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_user_registration';
    }
}
