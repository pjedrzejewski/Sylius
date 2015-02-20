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

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractResourceType
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
                'label' => 'sylius.form.user.password',
            ))
            ->add('plainPassword', 'password', array(
                'label' => 'sylius.form.user.password',
            ))
            ->add('enabled', 'checkbox', array(
                'label' => 'sylius.form.user.enabled',
            ))
            ->add('groups', 'sylius_group_choice', array(
                'label'    => 'sylius.form.user.groups',
                'multiple' => true,
                'required' => false,
            ))
            ->add('authorizationRoles', 'sylius_role_choice', array(
                'label'    => 'sylius.form.user.roles',
                'multiple' => true,
                'expanded' => true,
                'required' => false
            ))
            ->remove('username')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_user';
    }
}
