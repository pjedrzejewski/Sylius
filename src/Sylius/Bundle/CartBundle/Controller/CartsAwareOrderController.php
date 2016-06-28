<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CartBundle\Controller;

use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Order\Model\OrderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class CartsAwareOrderController extends ResourceController
{
    /**
     * Displays current cart summary page.
     * The parameters includes the form created from `sylius_cart` type.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function summaryAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $cart = $this->getCurrentCart();

        $form = $this->resourceFormFactory->create($configuration, $cart);

        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH']) && $form->submit($request, !$request->isMethod('PATCH'))->isValid()) {
            $cart = $form->getData();

            $event = $this->eventDispatcher->dispatch(CartEvents::PRE_SUMMARY_SAVE, $configuration, $cart);

            if ($event->isStopped() && !$configuration->isHtmlRequest()) {
                throw new HttpException($event->getErrorCode(), $event->getMessage());
            }

            if ($event->isStopped()) {
                $this->flashHelper->addFlashFromEvent($configuration, $event);

                return $this->redirectHandler->redirectToResource($configuration, $cart);
            }

            $this->manager->flush();
            $this->eventDispatcher->dispatchPostEvent(CartEvents::POST_SUMMARY_SAVE, $configuration, $cart);

            if (!$configuration->isHtmlRequest()) {
                return $this->viewHandler->handle($configuration, View::create(null, 204));
            }

            $this->flashHelper->addSuccessFlash($configuration, '', $cart);

            return $this->redirectHandler->redirectToResource($configuration, $cart);
        }

        if (!$configuration->isHtmlRequest()) {
            return $this->viewHandler->handle($configuration, View::create($form, 400));
        }

        $view = View::create()
            ->setData([
                'cart' => $cart,
                'form' => $form->createView(),
            ])
            ->setTemplate($configuration->getTemplate('summary.html'))
        ;

        return $this->viewHandler->handle($configuration, $view);
    }

    /**
     * Adds item to cart.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function addItemAction(Request $request)
    {
    }

    /**
     * @return Response
     */
    public function removeItemAction(Request $request)
    {
    }

    /**
     * @return OrderInterface
     */
    private function getCurrentCart()
    {
        return $this->container->get('sylius.context.cart')->getCart();
    }
}
