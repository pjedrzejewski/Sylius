<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Controller;

use FOS\RestBundle\View\View;
use Gedmo\Loggable\Entity\LogEntry;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ProductBundle\Controller\ProductController as BaseProductController;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\SearchBundle\Query\TaxonQuery;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Product controller.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class ProductController extends BaseProductController
{
    /**
     * List products categorized under given taxon.
     *
     * @param Request $request
     * @param string  $permalink
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     */
    public function indexByTaxonAction(Request $request, $permalink)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $criteria = $request->get('sylius_filter_form');
        unset($criteria['_token'], $criteria['filter']);

        if ($request->attributes->has('_sylius_entity')) {
            $taxon = $request->attributes->get('_sylius_entity');
        } else {
            $taxon = $this->container->get('sylius.repository.taxon')
                ->findOneByPermalink($permalink);

            if (!isset($taxon)) {
                throw new NotFoundHttpException('Requested taxon does not exist.');
            }
        }

        /**
         * when using elastic search if you want to setup multiple indexes and control
         * them separately you can do so by adding the index service with a setter
         *
         * ->setTargetIndex($this->get('fos_elastica.index.my_own_index'))
         *
         * where my_own_index is the index name used in the configuration
         * fos_elastica:
         *      indexes:
         *          my_own_index:
         */
        $finder = $this->container->get('sylius_search.finder')
            ->setFacetGroup('categories_set')
            ->find(new TaxonQuery($taxon, $request->query->get('filters', array())));

        $config = $this->container->getParameter("sylius_search.config");

        $paginator = $finder->getPaginator();

        return $this->renderResults(
            $configuration,
            $taxon,
            $paginator,
            'indexByTaxon.html',
            $request->get('page', 1),
            $finder->getFacets(),
            $config['filters']['facets'],
            $finder->getFilters(),
            $this->container->get('sylius_search.request_handler')->getQuery(),
            $this->container->get('sylius_search.request_handler')->getSearchParam(),
            $this->container->getParameter('sylius_search.request.method')
        );
    }

    /**
     * List products categorized under given taxon (fetch by its ID).
     *
     * @param Request $request
     * @param integer $id
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     */
    public function indexByTaxonIdAction(Request $request, $id)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $taxon = $this->container->get('sylius.repository.taxon')->find($id);

        if (!isset($taxon)) {
            throw new NotFoundHttpException('Requested taxon does not exist.');
        }

        $paginator = $this->repository->createByTaxonPaginator($taxon);

        return $this->renderResults($configuration, $taxon, $paginator, 'productIndex.html', $request->get('page', 1));
    }


    /**
     * Show product details in frontend.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     */
    public function detailsAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $channel = $this->container->get('sylius.context.channel')->getChannel();
        $product = $this->findOr404($configuration);

        if (!$product->getChannels()->contains($channel)) {
            throw new NotFoundHttpException(sprintf(
                'Requested %s does not exist for channel: %s.',
                $this->metadata->getName(),
                $channel->getName()
            ));
        }

        $view = View::create()
            ->setTemplate($configuration->getTemplate('show.html'))
            ->setTemplateVar($this->metadata->getName())
            ->setData($product)
        ;

        return $this->viewHandler->handle($view);
    }

    /**
     * Get product history changes.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     */
    public function historyAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        /** @var $product ProductInterface */
        $product = $this->findOr404($configuration);

        $repository = $this->get('doctrine')->getManager()->getRepository(LogEntry::class);

        $variants = array();
        foreach ($product->getVariants() as $variant) {
            $variants[] = $repository->getLogEntries($variant);
        }

        $attributes = array();
        foreach ($product->getAttributes() as $attribute) {
            $attributes[] = $repository->getLogEntries($attribute);
        }

        $options = array();
        if (empty($variants)) {
            foreach ($product->getOptions() as $option) {
                $options[] = $repository->getLogEntries($option);
            }
        }

        $view = View::create()
            ->setTemplate($configuration->getTemplate('history.html'))
            ->setData(array(
                'product' => $product,
                'logs'    => array(
                    'product'    => $repository->getLogEntries($product),
                    'variants'   => $variants,
                    'attributes' => $attributes,
                    'options'    => $options,
                ),
            ))
        ;

        return $this->viewHandler->handle($view);
    }

    /**
     * Render product filter form.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function filterFormAction(Request $request)
    {
        return $this->container->get('templating')->renderResponse('SyliusWebBundle:Backend/Product:filterForm.html.twig', array(
            'form' => $this->container->get('form.factory')->createNamed('criteria', 'sylius_product_filter', $request->query->get('criteria'))->createView()
        ));
    }

    // @todo refactor this when PRs about API & search get merged
    public function searchAction(Request $request)
    {
        if (!$request->query->has('criteria')) {
            throw new NotFoundHttpException();
        }

        /** @var $products ProductInterface[] */
        $results  = array();
        $products = $this->container->get('sylius.repository.product')->createFilterPaginator($request->query->get('criteria'));
        $helper   = $this->container->get('sylius.templating.helper.currency');
        foreach ($products as $product) {
            $results[] = array(
                'id'        => $product->getMasterVariant()->getId(),
                'name'      => $product->getName(),
                'image'     => $product->getImage()->getPath(),
                'price'     => $helper->convertAndFormatAmount($product->getMasterVariant()->getPrice()),
                'raw_price' => $helper->convertAndFormatAmount($product->getMasterVariant()->getPrice(), null, true),
                'desc'      => $product->getShortDescription(),
            );
        }

        return new JsonResponse($results);
    }

    /**
     * @param Request $request
     * @param array $criteria
     *
     * @return null|ProductInterface
     */
    public function findOr404(RequestConfiguration $configuration)
    {
        $request = $configuration->getRequest();

        if ($request->attributes->has('_sylius_entity')) {
            return $request->attributes->get('_sylius_entity');
        }

        return parent::findOr404($configuration);
    }

    private function renderResults(
        RequestConfiguration $configuration,
        TaxonInterface $taxon,
        Pagerfanta $results,
        $template, $page,
        $facets = null,
        $facetTags = null,
        $filters = null,
        $searchTerm = null,
        $searchParam = null,
        $requestMethod = null
    )
    {
        $results->setCurrentPage($page, true, true);
        $results->setMaxPerPage($configuration->getPaginationMaxPerPage());

        $view = View::create()
            ->setTemplate($configuration->getTemplate($template))
            ->setData(array(
                'taxon'    => $taxon,
                'products' => $results,
                'facets'   => $facets,
                'facetTags' => $facetTags,
                'filters' => $filters,
                'searchTerm' => $searchTerm,
                'searchParam' => $searchParam,
                'requestMethod' => $requestMethod
            ))
        ;

        return $this->viewHandler->handle($view);
    }
}
