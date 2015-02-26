<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\UserBundle\Form\Model\ChangePassword;
use Sylius\Bundle\UserBundle\Form\Model\PasswordReset;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sylius\Bundle\UserBundle\Form\Type\UserChangePasswordType;
use Sylius\Bundle\UserBundle\Form\Type\UserRequestPasswordResetType;
use Sylius\Bundle\UserBundle\UserEvents;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 */
class UserController extends ResourceController
{
    public function updateProfileAction(Request $request)
    {
        $resource = $this->getUser();
        $form     = $this->getForm($resource);

        if (in_array($request->getMethod(), array('POST', 'PUT', 'PATCH')) && $form->submit($request, !$request->isMethod('PATCH'))->isValid()) {
            $this->domainManager->update($resource);

            if ($this->config->isApiRequest()) {
                return $this->handleView($this->view($resource, 204));
            }

            return $this->redirectHandler->redirectTo($resource);
        }

        if ($this->config->isApiRequest()) {
            return $this->handleView($this->view($form, 400));
        }

        $view = $this
            ->view()
            ->setTemplate($this->config->getTemplate('updateProfile.html'))
            ->setData(array(
                $this->config->getResourceName() => $resource,
                'form'                           => $form->createView(),
            ))
        ;

        return $this->handleView($view);
    }

    public function changePasswordAction(Request $request)
    {
        $user = $this->getUser();
        $changePassword = new ChangePassword();
        $form = $this->createForm(new UserChangePasswordType(), $changePassword);

        if (in_array($request->getMethod(), array('POST', 'PUT', 'PATCH')) && $form->submit($request, !$request->isMethod('PATCH'))->isValid()) {
            $encoderFactory = $this->get('security.encoder_factory');

            $encoder = $encoderFactory->getEncoder($user);
            $validPassword = $encoder->isPasswordValid(
                $user->getPassword(),
                $changePassword->getCurrentPassword(),
                $user->getSalt()
            );

            if ($validPassword) {
                $user->setPlainPassword($changePassword->getNewPassword());

                $this->domainManager->update($user);
                $url = $this->generateUrl('sylius_account_homepage');

                $this->addFlash('success', 'sylius.account.password.change_success');

                return new RedirectResponse($url);
            }

            $this->addFlash('error', 'sylius.account.password.invalid');
        }

        if ($this->config->isApiRequest()) {
            return $this->handleView($this->view($form, 400));
        }

        return $this->render(
            'SyliusWebBundle:Frontend/Account:changePassword.html.twig',
            array(
                'form'  => $form->createView(),
            )
        );
    }

    public function requestPasswordResetAction(Request $request)
    {
        $passwordReset = new PasswordReset();
        $form = $this->createForm(new UserRequestPasswordResetType(), $passwordReset);

        if (in_array($request->getMethod(), array('POST', 'PUT', 'PATCH')) && $form->submit($request, !$request->isMethod('PATCH'))->isValid()) {
            $user = $this->getRepository()->findOneBy(array('email' => $passwordReset->getEmail()));

            if (null !== $user) {
                $this->addFlash('success', 'sylius.account.password.reset.success');

                $dispatcher = $this->get('event_dispatcher');

                $event = new GenericEvent($user);
                $dispatcher->dispatch(UserEvents::REQUEST_PASSWORD_RESET, $event);

                return $this->render(
                    'SyliusWebBundle:Frontend/Account:resetPassword.html.twig',
                    array(
                        'form'  => $form->createView(),
                    )
                );
            }

            $this->addFlash('error', 'sylius.account.email.not_exist');
            $this->addFlash('error', 'sylius.account.password.reset.failed');
        }
    
        return $this->render(
            'SyliusWebBundle:Frontend/Account:resetPassword.html.twig',
            array(
                'form'  => $form->createView(),
            )
        );
    }

    public function resetPasswordAction(Request $request)
    {
        # code...
    }

    protected function addFlash($type, $message)
    {
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add($type, $translator->trans($message, array(), 'flashes'));
    }
}
