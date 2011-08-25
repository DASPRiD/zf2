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
 * Date value.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Property
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Date implements Value
{
    /**
     * Year.
     * 
     * @var integer
     */
    protected $year;

    /**
     * Month.
     * 
     * @var integer
     */
    protected $month;
    
    /**
     * Day.
     * 
     * @var integer
     */
    protected $day;
    
    /**
     * Create a new date value.
     * 
     * @param  mixed   $date
     * @return void
     */
    public function __construct($date = null)
    {
        if ($date === null) {
            $date = time();
        }
        
        $this->setDate($date);
    }
    
    /**
     * Set date.
     * 
     * @param  mixed   $date
     * @return self
     */
    public function setDate($dateTime)
    {
        if (is_numeric($dateTime)) {
            $timestamp = (int) $dateTime;
        } elseif (is_array($dateTime)) {
            $values   = array();
            $required = array(
                'year'   => array(0, 9999),
                'month'  => array(1, 12),
                'day'    => array(1, 31),
            );
            
            foreach ($required as $key => $restrictions) {
                if (!isset($dateTime[$key]) || !is_numeric($dateTime[$key])) {
                    throw new Exception\InvalidArgumentException(sprintf('Supplied date array is missing %s element', $key));
                } elseif ($dateTime[$key] < $restrictions[0]) {
                    throw new Exception\InvalidArgumentException(sprintf('%s element is lower than %d', $key, $restrictions[0]));
                } elseif ($dateTime[$key] > $restrictions[1]) {
                    throw new Exception\InvalidArgumentException(sprintf('%s element is greater than %d', $key, $restrictions[1]));
                }
                
                $values[] = (int) $dateTime[$key];
            }
            
            $dateString = vsprintf('%04d%02d%02d', $values);
        } elseif ($dateTime instanceof \DateTime) {
            $dateTimeString = $dateTime->format('Ymd');
        } elseif ($dateTime instanceof \Zend\Date\DateObject) {
            $dateTimeString = $dateTime->toString('yyyyMMdd');
        } else {
            throw new Exception\InvalidArgumentException('Supplied date is neither a unix timestamp, an array nor an instance of \DateTime or \Zend\Date\Date');
        }
        
        if (isset($timestamp)) {
            $dateString = gmdate('Ymd', $timestamp);
        }
        
        sscanf(
            $dateTimeString, '%04d%02d%02d',
            $this->year, $this->month, $this->day
        );
        
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
        if (!preg_match('(^(?<year>\d{4})(?<month>\d{2})(?<day>\d{2}))S', $string, $match)) {
            return null;
        }
        
        return new self($match);
    }
}
