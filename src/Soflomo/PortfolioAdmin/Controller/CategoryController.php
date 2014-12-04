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
 * @package     Soflomo\PortfolioAdmin
 * @subpackage  Controller
 * @author      Jurian Sluiman <jurian@soflomo.com>
 * @copyright   2013 Jurian Sluiman.
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://soflomo.com
 * @version     @@PACKAGE_VERSION@@
 */

namespace Soflomo\PortfolioAdmin\Controller;

use Soflomo\Portfolio\Entity\Category as CategoryEntity;
use Soflomo\Portfolio\Entity\Portfolio     as PortfolioEntity;
use Soflomo\Portfolio\Exception;
use Soflomo\Portfolio\Options\ModuleOptions;
use Soflomo\PortfolioAdmin\Form\Category    as CategoryForm;
use Soflomo\PortfolioAdmin\Service\Category as CategoryService;
use Zend\Mvc\Controller\AbstractActionController;

class CategoryController extends AbstractActionController
{
    /**
     * @var CategoryService
     */
    protected $service;

    /**
     * @var CategoryForm
     */
    protected $form;

    /**
     * @var ModuleOptions
     */
    protected $options;

    public function __construct(CategoryService $service, CategoryForm $form, ModuleOptions $options = null)
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
        $portfolio       = $this->getPortfolio();
        $categories = $this->getRepository()->findAll();

        return array(
            'portfolio'       => $portfolio,
            'categories' => $categories,
        );
    }

    public function viewAction()
    {
        $portfolio     = $this->getPortfolio();
        $category = $this->getCategory();

        $this->addPage(array(
            'label'  => $category->getName(),
            'route'  => 'zfcadmin/portfolio/category/view',
            'params' => array('portfolio'   => $portfolio->getSlug(), 'category' => $category->getId()),
            'active' => true,
        ));

        return array(
            'portfolio'     => $portfolio,
            'category' => $category,
        );
    }

    public function createAction()
    {
        $portfolio     = $this->getPortfolio();
        $category = $this->getCategory(true);
        $form     = $this->getForm();
        $form->bind($category);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getService()->create($category);

                return $this->redirect()->toRoute('zfcadmin/portfolio/category/view', array(
                    'portfolio'     => $portfolio->getSlug(),
                    'category' => $category->getId(),
                ));
            }
        }

        $this->addPage(array(
            'label'  => 'New category',
            'route'  => 'zfcadmin/portfolio/category/create',
            'params' => array('portfolio'   => $portfolio->getSlug()),
            'active' => true,
        ));

        return array(
            'portfolio'    => $portfolio,
            'form'    => $form,
        );
    }

    public function updateAction()
    {
        $portfolio     = $this->getPortfolio();
        $category = $this->getCategory();
        $form     = $this->getForm();
        $form->bind($category);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getService()->update($category);

                return $this->redirect()->toRoute('zfcadmin/portfolio/category/update', array(
                    'portfolio'     => $portfolio->getSlug(),
                    'category' => $category->getId(),
                ));
            }
        }

        $this->addPage(array(
            'label'  => $category->getName(),
            'route'  => 'zfcadmin/portfolio/category/view',
            'params' => array('portfolio'   => $portfolio->getSlug(), 'category' => $category->getId()),
            'active' => true,
            'pages' => array(
                array(
                    'label'  => 'Update category',
                    'route'  => 'zfcadmin/portfolio/category/update',
                    'params' => array('portfolio'   => $portfolio->getSlug(), 'category' => $category->getId()),
                    'active' => true,
                ),
            ),
        ));

        return array(
            'portfolio'     => $portfolio,
            'category' => $category,
            'form'     => $form,
        );
    }

    public function deleteAction()
    {
        $portfolio     = $this->getPortfolio();
        $category = $this->getCategory();
        $service  = $this->getService();

        $service->delete($category);

        return $this->redirect()->toRoute('zfcadmin/portfolio', array(
            'portfolio' => $portfolio->getSlug(),
        ));
    }

    protected function getPortfolio()
    {
        $slug = $this->params('portfolio');
        $repo = $this->getService()->getPortfolioRepository();
        $portfolio = $repo->findOneBySlug($slug);

        if (null === $portfolio) {
            throw new Exception\PortfolioNotFoundException(sprintf(
                'Portfolio with slug "%s" not found', $slug
            ));
        }

        return $portfolio;
    }

    protected function getCategory($create = false)
    {
        if (true === $create) {
            $class   = $this->getOptions()->getCategoryEntityClass();
            $category = new $class;

            return $category;
        }

        $id       = $this->params('category');
        $category = $this->getRepository()->find($id);

        if (null === $category) {
            throw new Exception\CategoryNotFoundException(sprintf(
                'Category with id "%s" not found', $id
            ));
        }

        return $category;
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
