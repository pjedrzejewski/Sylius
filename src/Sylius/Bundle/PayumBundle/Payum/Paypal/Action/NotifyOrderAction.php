<?php

/*
* This file is part of the Sylius package.
*
* (c) Paweł Jędrzejewski
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Sylius\Bundle\PayumBundle\Payum\Paypal\Action;

use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Notify;
use Payum\Core\Request\Sync;
use SM\Factory\FactoryInterface;
use Sylius\Bundle\PayumBundle\Payum\Action\AbstractPaymentStateAwareAction;
use Sylius\Bundle\PayumBundle\Payum\Request\GetStatus;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Resource\Manager\ResourceManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class NotifyOrderAction extends AbstractPaymentStateAwareAction
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var ResourceManagerInterface
     */
    protected $manager;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param ResourceManagerInterface            $manager
     * @param FactoryInterface         $factory
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, ResourceManagerInterface $manager, FactoryInterface $factory)
    {
        parent::__construct($factory);

        $this->eventDispatcher = $eventDispatcher;
        $this->manager   = $manager;
    }

    /**
     * {@inheritDoc}
     *
     * @param $request Notify
     */
    public function execute($request)
    {
        if (!$this->supports($request)) {
            throw RequestNotSupportedException::createActionNotSupported($this, $request);
        }

        /** @var $payment PaymentInterface */
        $payment = $request->getModel();

        $this->payment->execute(new Sync($payment));

        $status = new GetStatus($payment);
        $this->payment->execute($status);

        $nextState = $status->getValue();

        $this->updatePaymentState($payment, $nextState);

        $this->manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Notify &&
            $request->getToken() &&
            $request->getModel() instanceof PaymentInterface
        ;
    }
}
