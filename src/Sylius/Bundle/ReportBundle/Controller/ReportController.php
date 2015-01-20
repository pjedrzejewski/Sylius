<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ReportBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sylius\Component\Report\Renderer\TableRenderer;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 */
class ReportController extends ResourceController
{
<<<<<<< HEAD
    public function renderAction(Request $request)
    {
        $rendererType = "chart";
        $renderer = $this->get(sprintf("sylius.form.type.renderer.%s", $rendererType));
        return $renderer->render(array(), array('template' => 0, 'type' => 'line'));
=======

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function renderAction(Request $request)
    {
        $report = $this->findOr404($request);

        $dataFetcher = $this->getDataFetcherRegistry()->get($report->getDataFetcher());
        $data = $dataFetcher->fetch($report->getDataFetcherConfiguration());
        var_dump($data);
    }

    private function getDataFetcherRegistry()
    {
        return $this->get('sylius.registry.report.data_fetcher');
    }

    private function getRendererRegistry()
    {
        return $this->get('sylius.registry.report.renderer');
>>>>>>> test dataFetcher
    }
}
