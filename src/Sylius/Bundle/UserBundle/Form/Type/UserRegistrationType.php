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

use FOS\UserBundle\Form\Type\ProfileFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends ProfileFormType
{
    /** @var string */
    private $dataClass;

    /**
     * {@inheritdoc}
     */
    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

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
            ->add('plainPassword', 'password', array(
                'label' => 'sylius.form.user.password',
            ))
        ;

        $this->buildUserForm($builder, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_user_registration';
    }
}
