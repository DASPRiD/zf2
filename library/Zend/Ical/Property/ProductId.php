<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Property
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @namespace
 */
namespace Zend\Ical\Property;

/**
 * Product ID property.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Property
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ProductId extends AbstractProperty
{
    /**
     * Product ID.
     * 
     * @var string
     */
    protected $productId;
    
    /**
     * Create a new product ID property.
     * 
     * @param  string $productId
     * @return void
     */
    public function __construct($productId = '-//Zend//NONSGML Zend_Ical//EN')
    {
        $this->setProductId($productId);
    }
    
    /**
     * Set product ID.
     * 
     * @param  string $productId
     * @return self
     */
    public function setProductId($productId)
    {
        $this->productId = (string) $productId;
        return $this;
    }
    
    /**
     * Get product ID.
     * 
     * @return string
     */
    public function getProductId()
    {
        return $this->productId;
    }
}
