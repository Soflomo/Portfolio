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

namespace Soflomo\Portfolio\Controller;

use DateTime;
use BaconStringUtils\Slugifier;

use Soflomo\Portfolio\Exception;
use Soflomo\Portfolio\Options\ModuleOptions;
use Soflomo\Portfolio\Repository\Item as ItemRepository;
use Doctrine\ORM\EntityRepository     as PortfolioRepository;

use Zend\Mvc\Controller\AbstractActionController;

class ItemController extends AbstractActionController
{
    /**
     * @var ItemRepository
     */
    protected $itemRepository;

    /**
     * @var  PortfolioRepository
     */
    protected $portfolioRepository;

    /**
     * @var ModuleOptions
     */
    protected $options;

    public function __construct(PortfolioRepository $portfolioRepository, ItemRepository $itemRepository, ModuleOptions $options = null)
    {
        $this->portfolioRepository = $portfolioRepository;
        $this->itemRepository      = $itemRepository;

        if (null !== $options) {
            $this->options = $options;
        }
    }

    public function getPortfolioRepository()
    {
        return $this->portfolioRepository;
    }

    public function getItemRepository()
    {
        return $this->itemRepository;
    }

    public function getOptions()
    {
        if (null === $this->options) {
            $this->options = new ModuleOptions;
        }

        return $this->options;
    }

    public function indexAction()
    {
        $portfolio = $this->getPortfolio();
        $items     = $this->getItemRepository()->findAllByPortfolio($portfolio);

        return array(
            'portfolio' => $portfolio,
            'items'     => $items,
        );
    }

    public function viewAction()
    {
        $portfolio = $this->getPortfolio();
        $id        = $this->params('item');
        $item      = $this->getItemRepository()->findItem($portfolio, $id);

        if (null === $item) {
            throw new Exception\ItemNotFoundException(sprintf(
                'Item id "%s" not found', $id
            ));
        }

        $slugifier = new Slugifier;
        $slug      = $slugifier->slugify($item->getTitle());
        if ($slug !== $this->params('slug') ) {
            return $this->redirect()->toRoute(null, array(
                'item' => $item->getId(),
                'slug'    => $slug,
            ))->setStatusCode(301);
        }

        return array(
            'portfolio' => $portfolio,
            'item'      => $item,
        );
    }

    protected function getPortfolio()
    {
        $page      = $this->getPage();
        $id        = $page->getModuleId();
        $portfolio = $this->getPortfolioRepository()->find($id);

        if (null === $portfolio) {
            throw new Exception\PortfolioNotFoundException(sprintf(
                'Cannot find a portfolio with id "%s"', $id
            ));
        }

        return $portfolio;
    }

    protected function getPage()
    {
        return $this->getEvent()->getParam('page');
    }
}