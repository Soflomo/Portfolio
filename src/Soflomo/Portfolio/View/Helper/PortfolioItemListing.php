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

namespace Soflomo\Portfolio\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Doctrine\ORM\EntityRepository     as PortfolioRepository;
use Soflomo\Portfolio\Repository\Item as ItemRepository;
use Soflomo\Portfolio\Exception;

class PortfolioItemListing extends AbstractHelper
{
    const DEFAULT_ITEM_LIMIT = 10;

    protected $repository;

    public function __construct(PortfolioRepository $portfolioRepository, ItemRepository $itemRepository)
    {
        $this->portfolioRepository = $portfolioRepository;
        $this->itemRepository      = $itemRepository;
    }

    public function __invoke($portfolio, $limit = null,  $category = null)
    {
        $limit  = $limit ?: self::DEFAULT_ITEM_LIMIT;

        if (null === $category) {
            return $this->getListing($portfolio, $limit);
        }

        return $this->getCategoryListing($portfolio, $category, $limit);
    }

    protected function getCategoryListing($portfolio, $category, $limit)
    {
        $portfolio  = $this->getPortfolio($portfolio);
        $limit = $limit ?: self::DEFAULT_ITEM_LIMIT;
        $page  = 1;

        $paginator = $this->getItemRepository()->findCategoryListing($portfolio, $category, $page, $limit);
        return $paginator->getCurrentItems();
    }

    protected function getListing($portfolio, $limit)
    {
        $portfolio  = $this->getPortfolio($portfolio);
        $limit = $limit ?: self::DEFAULT_ITEM_LIMIT;
        $page  = 1;

        $paginator = $this->getItemRepository()->findListing($portfolio, $page, $limit);
        return $paginator->getCurrentItems();
    }

    protected function getPortfolio($idOrSlug)
    {
        if (is_int($idOrSlug)) {
            $portfolio = $this->getPortfolioRepository()->find($idOrSlug);
        } else {
            $portfolio = $this->getPortfolioRepository()->findOneBySlug($idOrSlug);
        }

        if (null === $portfolio) {
            throw new Exception\PortfolioNotFoundException(sprintf(
                'Portfolio with slug "%s" not found', $slug
            ));
        }

        return $portfolio;
    }

    protected function getPortfolioRepository()
    {
        return $this->portfolioRepository;
    }

    protected function getItemRepository()
    {
        return $this->itemRepository;
    }
}
