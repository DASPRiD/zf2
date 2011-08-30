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

use Zend\Ical\Exception;

/**
 * UTC offset value.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Property
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class UtcOffset implements Value
{
    /**
     * Whether the offset is positive.
     * 
     * @var boolean
     */
    protected $positive;
    
    /**
     * Hour offset.
     * 
     * @var integer
     */
    protected $hours;

    /**
     * Minute offset.
     * 
     * @var integer
     */
    protected $minutes;
    
    /**
     * Second offset.
     * 
     * @var integer
     */
    protected $seconds;
    
    /**
     * Create a new offset value.
     * 
     * @param  boolean $positive
     * @param  integer $hours
     * @param  integer $minutes
     * @param  integer $seconds
     * @return void
     */
    public function __construct($positive, $hours, $minutes, $seconds = 0)
    {
        $this->setOffset($positive, $hours, $minutes, $seconds);
    }
    
    /**
     * Set offset.
     * 
     * @param  boolean $positive
     * @param  integer $hours
     * @param  integer $minutes
     * @param  integer $seconds
     * @return self
     */
    public function setOffset($positive, $hours, $minutes, $seconds = 0)
    {
        $this->positive = (bool) $positive;
        
        if ($hours < 0 || $hours > 23) {
            throw new Exception\InvalidArgumentException('Hours must be between 0 and 23');
        } elseif ($minutes < 0 || $minutes > 59) {
            throw new Exception\InvalidArgumentException('Minutes must be between 0 and 59');
        } elseif ($seconds < 0 || $seconds > 59) {
            throw new Exception\InvalidArgumentException('Seconds must be between 0 and 59');
        }
        
        $this->hours   = (int) $hours;
        $this->minutes = (int) $minutes;
        $this->seconds = (int) $seconds;
        
        return $this;
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
        if (!preg_match('(^(?<positive>[+-])(?<hours>\d{2})(?<minutes>\d{2})(?<seconds>\d{2})?$)S', $string, $match)) {
            return null;
        }
        
        return new self($match['positive'] === '+', $match['hours'], $match['minutes'], isset($match['seconds']) ? $match['seconds'] : 0);
    }
}
