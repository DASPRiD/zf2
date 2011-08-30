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
namespace Zend\Ical\Property\Value;

/**
 * Raw value.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Property
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Raw implements Value
{
    /**
     * String.
     * 
     * @var string
     */
    protected $string;
    
    /**
     * Create a new raw value.
     * 
     * @param  string $string
     * @return void
     */
    public function __construct($string)
    {
        $this->setRaw($string);
    }
    
    /**
     * Set raw.
     * 
     * @param  string $string
     * @return self
     */
    public function setRaw($string)
    {                
        $this->string = (string) $string;
        return $this;
    }
    
    /**
     * Get raw.
     * 
     * @return string
     */
    public function getRaw()
    {
        return $this->string;
    }
    
    /**
     * fromString(): defined by Value interface.
     * 
     * @see    Value::fromString()
     * @param  string $string
     * @return Value
     */
    public static function fromString($string)
    {
        return new self($string);
    }
}
