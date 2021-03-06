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

namespace Soflomo\Portfolio\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * Turn off strict options mode
     */
    protected $__strictMode__ = false;

    /**
     * @var  int
     */
    protected $adminListingLimit = 10;

    /**
     * @var int
     */
    protected $categoryListingLimit = 10;

    /**
     * @var string
     */
    protected $portfolioEntityClass;

     /**
     * @var string
     */
    protected $categoryEntityClass;

    /**
     * @var string
     */
    protected $itemEntityClass;

    /**
     * Getter for adminListingLimit
     *
     * @return mixed
     */
    public function getAdminListingLimit()
    {
        return $this->adminListingLimit;
    }

    /**
     * Setter for adminListingLimit
     *
     * @param mixed $adminListingLimit Value to set
     * @return self
     */
    public function setAdminListingLimit($adminListingLimit)
    {
        $this->adminListingLimit = $adminListingLimit;
        return $this;
    }

    /**
     * Getter for categoryListingLimit
     *
     * @return mixed
     */
    public function getCategoryListingLimit()
    {
        return $this->categoryListingLimit;
    }

    /**
     * Setter for categoryListingLimit
     *
     * @param mixed $categoryListingLimit Value to set
     * @return self
     */
    public function setCategoryListingLimit($categoryListingLimit)
    {
        $this->categoryListingLimit = $categoryListingLimit;
        return $this;
    }

    /**
     * Getter for portfolioEntityClass
     *
     * @return mixed
     */
    public function getPortfolioEntityClass()
    {
        return $this->portfolioEntityClass;
    }

    /**
     * Setter for portfolioEntityClass
     *
     * @param mixed $portfolioEntityClass Value to set
     * @return self
     */
    public function setPortfolioEntityClass($portfolioEntityClass)
    {
        $this->portfolioEntityClass = $portfolioEntityClass;
        return $this;
    }

    /**
     * Getter for categoryEntityClass
     *
     * @return mixed
     */
    public function getCategoryEntityClass()
    {
        return $this->categoryEntityClass;
    }

    /**
     * Setter for categoryEntityClass
     *
     * @param mixed $categoryEntityClass Value to set
     * @return self
     */
    public function setCategoryEntityClass($categoryEntityClass)
    {
        $this->categoryEntityClass = $categoryEntityClass;
        return $this;
    }

    /**
     * Getter for articleEntityClass
     *
     * @return mixed
     */
    public function getArticleEntityClass()
    {
        return $this->articleEntityClass;
    }

    /**
     * Getter for itemEntityClass
     *
     * @return mixed
     */
    public function getItemEntityClass()
    {
        return $this->itemEntityClass;
    }

    /**
     * Setter for itemEntityClass
     *
     * @param mixed $itemEntityClass Value to set
     * @return self
     */
    public function setItemEntityClass($itemEntityClass)
    {
        $this->itemEntityClass = $itemEntityClass;
        return $this;
    }
}
