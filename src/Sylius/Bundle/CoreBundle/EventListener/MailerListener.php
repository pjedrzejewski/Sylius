<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\EventListener;

use Sylius\Bundle\CoreBundle\Mailer\Emails;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Sylius\Component\Order\Model\CommentInterface;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Generic mailer listener.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class MailerListener
{
    /**
     * @var SenderInterface
     */
    protected $emailSender;

    public function __construct(SenderInterface $emailSender)
    {
        $this->emailSender = $emailSender;
    }

    /**
     * @param GenericEvent $event
     *
     * @throws UnexpectedTypeException
     */
    public function sendOrderConfirmationEmail(GenericEvent $event)
    {
        $order = $event->getSubject();

        if (!$order instanceof OrderInterface) {
            throw new UnexpectedTypeException(
                $order,
                'Sylius\Component\Core\Model\OrderInterface'
            );
        }

        $this->emailSender->send(Emails::ORDER_CONFIRMATION, array($order->getCustomer()->getEmail()), array('order' => $order));
    }

    /**
     * @param GenericEvent $event
     *
     * @throws UnexpectedTypeException
     */
    public function sendUserConfirmationEmail(GenericEvent $event)
    {
        $user = $event->getSubject();

        if (!$user instanceof UserInterface) {
            throw new UnexpectedTypeException(
                $user,
                'Sylius\Component\Core\Model\UserInterface'
            );
        }

        if (!$user->isEnabled()) {
            return;
        }

        $this->emailSender->send(Emails::USER_CONFIRMATION, array($user->getEmail()), array('user' => $user));
    }

    /**
     * @param GenericEvent $event
     *
     * @throws UnexpectedTypeException
     */
    public function sendOrderCommentEmail(GenericEvent $event)
    {
        $comment = $event->getSubject();

        if (!$comment instanceof CommentInterface) {
            throw new UnexpectedTypeException(
                $comment,
                'Sylius\Component\Order\Model\CommentInterface'
            );
        }

        if ($comment->getNotifyCustomer()) {
            $order = $comment->getOrder();
            $email = $order->getCustomer()->getEmail();

            $this->emailSender->send(Emails::ORDER_COMMENT, array($email), array(
                'order' => $order,
                'comment' => $comment,
            ));
        }
    }
}
