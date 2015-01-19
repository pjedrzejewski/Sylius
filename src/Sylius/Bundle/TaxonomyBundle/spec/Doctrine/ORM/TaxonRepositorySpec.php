<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\TaxonomyBundle\Doctrine\ORM;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Taxonomy\Model\TaxonomyInterface;

class TaxonRepositorySpec extends ObjectBehavior
{
    function let(EntityRepository $objectRepository, EntityManager $objectManager)
    {
        $this->beConstructedWith($objectRepository, $objectManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\TaxonomyBundle\Doctrine\ORM\TaxonRepository');
    }

    function it_finds_taxon_as_a_list(EntityRepository $objectRepository, TaxonomyInterface $taxonomy, QueryBuilder $queryBuilder, AbstractQuery $query)
    {
        $objectRepository->createQueryBuilder('o')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->addSelect('translation')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->leftJoin('o.translations', 'translation')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->where('o.taxonomy = :taxonomy')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->andWhere('o.parent IS NOT NULL')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->setParameter('taxonomy', $taxonomy)->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->orderBy('o.left')->shouldBeCalled()->willReturn($queryBuilder);

        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getResult()->shouldBeCalled();

        $this->getTaxonsAsList($taxonomy);
    }

    function it_finds_one_taxon_by_permalink(EntityRepository $objectRepository, QueryBuilder $queryBuilder, AbstractQuery $query)
    {
        $objectRepository->createQueryBuilder('o')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->addSelect('translation')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->leftJoin('o.translations', 'translation')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->where('translation.permalink = :permalink')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->setParameter('permalink', 'link')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->orderBy('o.left')->shouldBeCalled()->willReturn($queryBuilder);

        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getOneOrNullResult()->shouldBeCalled();

        $this->findOneByPermalink('link');
    }
}
