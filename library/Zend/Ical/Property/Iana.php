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
 * IANA registered property.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Property
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Iana extends AbstractProperty
{
    /**
     * Value.
     * 
     * @var string
     */
    protected $value;
    
    /**
     * Create a new IANA registered property.
     * 
     * @param  string $value
     * @return void
     */
    public function __construct($value)
    {
        $this->setValue($value);
    }
    
    /**
     * Set value.
     * 
     * @param  string $value
     * @return self
     */
    public function setProdId($value)
    {
        $this->value = (string) $value;
        return $this;
    }
    
    /**
     * Get value.
     * 
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
