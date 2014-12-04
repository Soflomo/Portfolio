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

return array(
    'soflomo_portfolio' => array(
        'portfolio_entity_class' => 'Soflomo\Portfolio\Entity\Portfolio',
        'category_entity_class'  => 'Soflomo\Portfolio\Entity\Category',
        'item_entity_class'      => 'Soflomo\Portfolio\Entity\Item',

        'admin_listing_limit'   => 10,
        'category_listing_limit' => 10,
    ),

    'ensemble_kernel' => array(
        'routes' => array(
            'portfolio' => array(
                'options' => array(
                    'defaults' => array(
                        'controller' => 'Soflomo\Portfolio\Controller\ItemController',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'view' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/:item[/:slug]',
                            'defaults' => array(
                                'action' => 'view',
                            ),
                            'constraints' => array(
                                'article' => '[0-9]+',
                                'slug'    => '[a-zA-Z0-9-_]+',
                            ),
                        ),
                    ),
                    'category' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/category/:category[/:page]',
                            'defaults' => array(
                                'action' => 'category',
                                'page'   => '1',
                            ),
                            'constraints' => array(
                                'category' => '[a-zA-Z0-9-_.]+',
                                'page'     => '[0-9]+',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'router' => array(
        'routes' => array(
            'zfcadmin' => array(
                'child_routes' => array(
                    'portfolio' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/portfolio/:portfolio[/:page]',
                            'defaults' => array(
                                'controller' => 'Soflomo\PortfolioAdmin\Controller\ItemController',
                                'action'     => 'index',
                                'page'       => '1',
                            ),
                            'constraints' => array(
                                'portfolio' => '[a-zA-Z0-9-_]+',
                                'page'      => '[0-9]+',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes'  => array(
                            'item' => array(
                                'type'    => 'segment',
                                'options' => array(
                                    'route' => '/item'
                                ),
                                'may_terminate' => false,
                                'child_routes'  => array(
                                    'view' => array(
                                        'type'    => 'segment',
                                        'options' => array(
                                            'route' => '/:item',
                                            'defaults' => array(
                                                'action' => 'view',
                                            ),
                                            'constraints' => array(
                                                'item' => '[0-9]+'
                                            ),
                                        ),
                                    ),
                                    'create' => array(
                                        'type'    => 'literal',
                                        'options' => array(
                                            'route' => '/new',
                                            'defaults' => array(
                                                'action' => 'create',
                                            ),
                                        ),
                                    ),
                                    'update' => array(
                                        'type'    => 'segment',
                                        'options' => array(
                                            'route' => '/:item/edit',
                                            'defaults' => array(
                                                'action' => 'update',
                                            ),
                                            'constraints' => array(
                                                'item' => '[0-9]+'
                                            ),
                                        ),
                                    ),
                                    'delete' => array(
                                        'type'    => 'segment',
                                        'options' => array(
                                            'route' => '/:item/delete',
                                            'defaults' => array(
                                                'action' => 'delete',
                                            ),
                                            'constraints' => array(
                                                'item' => '[0-9]+'
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            'category' => array(
                                'type'    => 'literal',
                                'options' => array(
                                    'route' => '/category',
                                    'defaults' => array(
                                        'controller' => 'Soflomo\PortfolioAdmin\Controller\CategoryController',
                                        'action'     => 'index',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes'  => array(
                                    'view' => array(
                                        'type'    => 'segment',
                                        'options' => array(
                                            'route' => '/:category',
                                            'defaults' => array(
                                                'action' => 'view',
                                            ),
                                            'constraints' => array(
                                                'category' => '[0-9]+'
                                            ),
                                        ),
                                    ),
                                    'create' => array(
                                        'type'    => 'literal',
                                        'options' => array(
                                            'route' => '/new',
                                            'defaults' => array(
                                                'action' => 'create',
                                            ),
                                        ),
                                    ),
                                    'update' => array(
                                        'type'    => 'segment',
                                        'options' => array(
                                            'route' => '/:category/edit',
                                            'defaults' => array(
                                                'action' => 'update',
                                            ),
                                            'constraints' => array(
                                                'category' => '[0-9]+'
                                            ),
                                        ),
                                    ),
                                    'delete' => array(
                                        'type'    => 'segment',
                                        'options' => array(
                                            'route' => '/:category/delete',
                                            'defaults' => array(
                                                'action' => 'delete',
                                            ),
                                            'constraints' => array(
                                                'category' => '[0-9]+'
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'ensemble_admin' => array(
        'routes' => array(
            'portfolio' => array(
                'portfolio' => array(
                    'type' => 'literal',
                    'options' => array(
                        'route' => '/',
                        'defaults' => array(
                            'controller' => 'Soflomo\PortfolioAdmin\Controller\IndexController',
                            'action'     => 'index'
                        ),
                    )
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'slug' => 'Soflomo\Portfolio\View\Helper\Slug'
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'Soflomo\Portfolio\Repository\Item'      => 'Soflomo\Portfolio\Factory\ItemRepositoryFactory',
            'Soflomo\Portfolio\Repository\Category'   => 'Soflomo\Portfolio\Factory\CategoryRepositoryFactory',
            'Soflomo\Portfolio\Repository\Portfolio' => 'Soflomo\Portfolio\Factory\PortfolioRepositoryFactory',

            'Soflomo\Portfolio\Hydrator\Strategy\CategoryStrategy' => 'Soflomo\Portfolio\Factory\CategoryHydratorStrategyFactory',

            'Soflomo\PortfolioAdmin\Form\Item'       => 'Soflomo\PortfolioAdmin\Factory\ItemFormFactory',
            'Soflomo\PortfolioAdmin\Form\Category'    => 'Soflomo\PortfolioAdmin\Factory\CategoryFormFactory',

            'Soflomo\PortfolioAdmin\Service\Item'    => 'Soflomo\PortfolioAdmin\Factory\ItemServiceFactory',
            'Soflomo\PortfolioAdmin\Service\Category' => 'Soflomo\PortfolioAdmin\Factory\CategoryServiceFactory',

        ),
    ),

    'controllers' => array(
        'factories' => array(
            'Soflomo\Portfolio\Controller\ItemController'       => 'Soflomo\Portfolio\Factory\ItemControllerFactory',
            'Soflomo\PortfolioAdmin\Controller\ItemController'  => 'Soflomo\PortfolioAdmin\Factory\ItemControllerFactory',
            'Soflomo\PortfolioAdmin\Controller\CategoryController' => 'Soflomo\PortfolioAdmin\Factory\CategoryControllerFactory',

            'Soflomo\PortfolioAdmin\Controller\IndexController' => 'Soflomo\PortfolioAdmin\Factory\IndexControllerFactory',
        ),
    ),

    'doctrine' => array(
        'driver' => array(
            'soflomo_portfolio' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'paths' => __DIR__ . '/mapping'
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Soflomo\Portfolio\Entity' => 'soflomo_portfolio'
                ),
            ),
        ),
        'entity_resolver' => array(
            'orm_default' => array(
                'resolvers' => array(
                    'Soflomo\Portfolio\Entity\PortfolioInterface' => 'Soflomo\Portfolio\Entity\Portfolio',
                    'Soflomo\Portfolio\Entity\CategoryInterface' => 'Soflomo\Portfolio\Entity\Category',
                    'Soflomo\Portfolio\Entity\ItemInterface'      => 'Soflomo\Portfolio\Entity\Item',
                ),
            ),
        ),
    ),
);
