<?php

/*
 * Astound Override of Sylius\Bundle\AddressingBundle\Form\Type;
 */

namespace Astound\Bundle\AddressingBundle\Form\Type;

use Sylius\Bundle\AddressingBundle\Form\Type\AddressType as BaseAddressType;
use Symfony\Component\Form\FormBuilderInterface;


/**
 * Address form type.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@sylius.pl>
 */
class AddressType extends BaseAddressType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('lphone1', 'text', array(
                'label' => 'Primary Phone'
            ))
            ->add('phone2', 'text', array(
                'required' => false,
                'label' => 'Alternate Phone'
            ))
        ;
    }

}
