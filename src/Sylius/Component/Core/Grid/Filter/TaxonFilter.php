<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Core\Grid\Filter;

use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class TaxonFilter implements FilterInterface
{
    const NAME = 'taxon';

    /**
     * {@inheritdoc}
     */
    public function apply(DataSourceInterface $dataSource, $name, $data, array $options)
    {
        $field = array_key_exists('field', $options) ? $options['field'] : $name;
        $multiple = array_key_exists('multiple', $options) ? $options['multiple'] : false;

        $expressionBuilder = $dataSource->getExpressionBuilder();

        if ($multiple) {
            $dataSource->restrict($expressionBuilder->equals($field, $data));
        } else {
            $dataSource->restrict($expressionBuilder->equals($field, $data));
        }
    }
}
