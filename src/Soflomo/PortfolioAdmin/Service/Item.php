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

namespace Soflomo\PortfolioAdmin\Service;

use Soflomo\Portfolio\Entity\ItemInterface as ItemEntity;
use Soflomo\Portfolio\Repository\Item      as ItemRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository          as PortfolioRepository;
use Zend\EventManager;

class Item implements EventManager\EventManagerAwareInterface
{
    protected $entityManager;
    protected $portfolioRepository;
    protected $itemRepository;

    protected $eventManager;

    public function __construct(EntityManager $em, PortfolioRepository $portfolioRepository, ItemRepository $itemRepository)
    {
        $this->entityManager       = $em;
        $this->portfolioRepository = $portfolioRepository;
        $this->itemRepository      = $itemRepository;
    }

    public function getRepository()
    {
        return $this->getItemRepository();
    }

    public function getPortfolioRepository()
    {
        return $this->portfolioRepository;
    }

    public function getItemRepository()
    {
        return $this->itemRepository;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

    public function create(ItemEntity $item)
    {
        $this->trigger(__FUNCTION__ . '.pre', array('item' => $item));

        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();

        $this->trigger(__FUNCTION__ . '.post', array('item' => $item));
    }

    public function update(ItemEntity $item)
    {
        $this->trigger(__FUNCTION__ . '.pre', array('item' => $item));

        $this->getEntityManager()->flush();

        $this->trigger(__FUNCTION__ . '.post', array('item' => $item));
    }

    public function delete(ItemEntity $item)
    {
        $this->trigger(__FUNCTION__ . '.pre', array('item' => $item));

        $this->getEntityManager()->remove($item);
        $this->getEntityManager()->flush();

        $this->trigger(__FUNCTION__ . '.post', array('item' => $item));
    }

    public function trigger($name, array $parameters = array())
    {
        $event = new EventManager\Event;
        $event->setTarget($this);
        $event->setName($name);
        $event->setParams($parameters);

        $this->getEventManager()->trigger($event);
    }

    /**
     * Getter for eventManager
     *
     * @return mixed
     */
    public function getEventManager()
    {
        if (null === $this->eventManager) {
            $this->setEventManager(new EventManager\EventManager);
        }
        return $this->eventManager;
    }

    /**
     * Setter for eventManager
     *
     * @param mixed $eventManager Value to set
     * @return self
     */
    public function setEventManager(EventManager\EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers(array(
            __CLASS__,
            get_called_class(),
        ));

        $this->eventManager = $eventManager;
        return $this;
    }

}