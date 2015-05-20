<?php
/**
 * Copyright (c) 2013 Soflomo.
 * All rights reserved.
 *
 * This license allows for redistribution, commercial and non-commercial, as
 * long as it is passed along unchanged and in whole, with credit to Soflomo.
 *
 * @author      Jurian Sluiman <jurian@soflomo.com>
 * @copyright   2013 Soflomo.
 * @license     http://creativecommons.org/licenses/by-nd/3.0/  CC-BY-ND-3.0
 * @link        http://soflomo.com
 */

namespace Soflomo\Portfolio\Factory;

use Soflomo\Portfolio\View\Helper\PortfolioItemListing;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PortfolioItemListingFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $portfolioRepository    = $sl->getServiceLocator()->get('Soflomo\Portfolio\Repository\Portfolio');
        $itemRepository = $sl->getServiceLocator()->get('Soflomo\Portfolio\Repository\Item');

        $helper = new PortfolioItemListing($portfolioRepository, $itemRepository);
        return $helper;
    }
}
