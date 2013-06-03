<?php
/**
 * Copyright (c) 2013 Jurian Sluiman.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of the
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @author      Jurian Sluiman <jurian@soflomo.com>
 * @copyright   2013 Jurian Sluiman.
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://soflomo.com
 */

namespace Soflomo\Portfolio\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

use Soflomo\Portfolio\Entity\Portfolio                    as PortfolioEntity;

use Doctrine\ORM\Tools\Pagination\Paginator               as DoctrinePaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use Zend\Paginator\Paginator;

class Item extends EntityRepository
{
    public function findAllByPortfolio(PortfolioEntity $portfolio)
    {
        $qb = $this->createQueryBuilder('i');
        $qb->andWhere('i.portfolio = :portfolio')
           ->setParameter('portfolio', $portfolio);

        return $qb->getQuery()->getResult();
    }

    public function findItem(PortfolioEntity $portfolio, $id)
    {
        $qb = $this->createQueryBuilder('i');
        $qb->andWhere('i.portfolio = :portfolio')
           ->setParameter('portfolio', $portfolio)
           ->andWhere('i.id = :id')
           ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findListing(PortfolioEntity $portfolio, $page, $limit)
    {
        $qb = $this->createQueryBuilder('i');
        $qb->andWhere('i.portfolio = :portfolio')
           ->setParameter('portfolio', $portfolio);

        $paginator = $this->getPaginator($qb->getQuery());
        $paginator->setCurrentPageNumber($page)
                  ->setItemCountPerPage($limit);

        return $paginator;
    }

    public function getPaginator(Query $query)
    {
        $paginator = new DoctrinePaginator($query);
        $adapter   = new PaginatorAdapter($paginator);

        return new Paginator($adapter);
    }
}