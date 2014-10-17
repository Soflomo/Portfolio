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

namespace Soflomo\PortfolioAdmin\Controller;

use Soflomo\Portfolio\Entity\Item       as ItemEntity;
use Soflomo\Portfolio\Entity\Portfolio  as PortfolioEntity;
use Soflomo\Portfolio\Exception;
use Soflomo\Portfolio\Options\ModuleOptions;
use Soflomo\PortfolioAdmin\Form\Item    as ItemForm;
use Soflomo\PortfolioAdmin\Service\Item as ItemService;
use Zend\Mvc\Controller\AbstractActionController;

class ItemController extends AbstractActionController
{
    /**
     * @var ItemService
     */
    protected $service;

    /**
     * @var ItemForm
     */
    protected $form;

    /**
     * @var ModuleOptions
     */
    protected $options;

    public function __construct(ItemService $service, ItemForm $form, ModuleOptions $options = null)
    {
        $this->service = $service;
        $this->form    = $form;

        if (null !== $options) {
            $this->options = $options;
        }
    }

    public function getService()
    {
        return $this->service;
    }

    public function getRepository()
    {
        return $this->getService()->getRepository();
    }

    public function getForm()
    {
        return $this->form;
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
        $portfolio   = $this->getPortfolio();
        $page        = $this->params('page');
        $limit       = $this->getOptions()->getAdminListingLimit();
        $paginator   = $this->getRepository()->findListing($portfolio, $page, $limit);

        return array(
            'portfolio' => $portfolio,
            'paginator' => $paginator,
        );
    }

    public function viewAction()
    {
        $portfolio = $this->getPortfolio();
        $item = $this->getItem($portfolio);

        $this->addPage(array(
            'label'  => $item->getTitle(),
            'route'  => 'zfcadmin/portfolio/item/view',
            'params' => array('portfolio' => $portfolio->getSlug(), 'item' => $item->getId()),
            'active' => true,
        ));

        return array(
            'portfolio' => $portfolio,
            'item'      => $item,
        );
    }

    public function createAction()
    {
        $portfolio = $this->getPortfolio();
        $item = $this->getItem($portfolio, true);
        $form = $this->getForm();
        $form->bind($item);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getService()->create($item);

                $this->flashMessenger()->addMessage('Item created successfully.');
                return $this->redirect()->toRoute('zfcadmin/portfolio/item/view', array(
                    'portfolio'    => $portfolio->getSlug(),
                    'item' => $item->getId(),
                ));
            }
        }

        $this->addPage(array(
            'label'  => 'New item',
            'route'  => 'zfcadmin/portfolio/item/create',
            'params' => array('portfolio' => $portfolio->getSlug()),
            'active' => true,
        ));

        return array(
            'portfolio' => $portfolio,
            'form'      => $form,
        );
    }

    public function updateAction()
    {
        $portfolio = $this->getPortfolio();
        $item = $this->getItem($portfolio);
        $form    = $this->getForm();
        $form->bind($item);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getService()->update($item);

                $this->flashMessenger()->addMessage('Item saved successfully.');
                return $this->redirect()->toRoute('zfcadmin/portfolio/item/update', array(
                    'portfolio' => $portfolio->getSlug(),
                    'item'      => $item->getId(),
                ));
            }
        }

        $this->addPage(array(
            'label'  => $item->getTitle(),
            'route'  => 'zfcadmin/portfolio/item/view',
            'params' => array('portfolio' => $portfolio->getSlug(), 'item' => $item->getId()),
            'active' => true,
            'pages' => array(
                array(
                    'label'  => 'Update item',
                    'route'  => 'zfcadmin/portfolio/item/update',
                    'params' => array('portfolio' => $portfolio->getSlug(), 'item' => $item->getId()),
                    'active' => true,
                ),
            ),
        ));

        return array(
            'portfolio' => $portfolio,
            'item'      => $item,
            'form'      => $form,
        );
    }

    public function deleteAction()
    {
        $portfolio    = $this->getPortfolio();
        $article = $this->getItem($portfolio);
        $service = $this->getService();

        $service->delete($article);

        $this->flashMessenger()->addMessage('Item deleted successfully.');
        return $this->redirect()->toRoute('zfcadmin/portfolio', array(
            'portfolio' => $portfolio->getSlug(),
        ));
    }

    protected function getPortfolio()
    {
        $slug      = $this->params('portfolio');
        $repo      = $this->getService()->getPortfolioRepository();
        $portfolio = $repo->findOneBySlug($slug);

        if (null === $portfolio) {
            throw new Exception\PortfolioNotFoundException(sprintf(
                'Portfolio with slug "%s" not found', $slug
            ));
        }

        return $portfolio;
    }

    protected function getItem(PortfolioEntity $portfolio, $create = false)
    {
        if (true === $create) {
            $class   = $this->getOptions()->getItemEntityClass();
            $item = new $class;
            $item->setPortfolio($portfolio);

            return $item;
        }

        $id   = $this->params('item');
        $item = $this->getRepository()->find($id);

        if (null === $item) {
            throw new Exception\ItemNotFoundException(sprintf(
                'Item with id "%s" not found', $id
            ));
        } elseif ($item->getPortfolio()->getId() !== $portfolio->getId()) {
            throw new Exception\ItemNotFoundException(sprintf(
                'Item with id "%s" is not part of portfolio %s', $id, $portfolio->getSlug()
            ));
        }

        return $item;
    }

    protected function addPage(array $config = array())
    {
        $admin = $this->getServiceLocator()->get('admin_navigation');
        $found = false;

        // We need to query the page ourselves as
        // $admin->findOneByRoute('zfcadmin/portfolio')
        // does not load the page by reference

        foreach ($admin->getPages() as $page) {
            if ($page->getRoute() === 'zfcadmin/portfolio') {
                $found = true;
                break;
            }
        }

        if (!$found) {
            return;
        }

        $page->addPage($config);
    }
}
