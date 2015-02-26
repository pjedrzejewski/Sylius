<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\UserBundle;

/**
 * Contains all events thrown in the SyliusUserBundle
 */
final class UserEvents
{
    /**
     * The REQUEST_PASSWORD_RESET event occurs when the resetting process is initialized.
     *
     * This event allows you to send mail with verification token.
     * The event listener method receives a Sylius\Bundle\UserBundle\Form\Model\PasswordReset instance.
     */
    const REQUEST_PASSWORD_RESET = 'sylius.user.password_reset.request';

    /**
     * The RESETTING_RESET_SUCCESS event occurs when the resetting form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     * The event listener method receives a Sylius\Bundle\UserBundle\Event\FormEvent instance.
     */
    const PASSWORD_RESET_SUCCESS = 'sylius.user.password_reset.success';
}
