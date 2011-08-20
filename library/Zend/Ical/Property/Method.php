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

use Zend\Ical,
    Zend\Ical\Exception;

/**
 * Method property.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Property
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Method extends AbstractProperty
{
    /**
     * Method.
     * 
     * @var string
     */
    protected $method;
    
    /**
     * Create a new method property.
     * 
     * @param  string $method
     * @return void
     */
    public function __construct($method)
    {
        $this->setMethod($method);
    }
    
    /**
     * Set method.
     * 
     * @param  string $method
     * @return self
     */
    public function setMethod($method)
    {
        if (!Ical::isIanaToken($method)) {
            throw new Exception\UnexpectedValueException(sprintf('"%s" is not a valid IANA token', $method));
        }
        
        $this->method = (string) $method;
        return $this;
    }
    
    /**
     * Get method.
     * 
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
}
