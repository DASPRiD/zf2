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
 * Calendar scale property.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Property
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class CalendarScale extends AbstractProperty
{
    /**
     * Calendar scale.
     * 
     * @var string
     */
    protected $calendarScale;
    
    /**
     * Create a new calendar scale property.
     * 
     * @param  string $calendarScale
     * @return void
     */
    public function __construct($calendarScale = 'GREGORIAN')
    {
        $this->setCalendarScale($calendarScale);
    }
    
    /**
     * Set calendar scale.
     * 
     * @param  string $calendarScale
     * @return self
     */
    public function setCalendarScale($calendarScale)
    {
        if (!Ical::isIanaToken($calendarScale)) {
            throw new Exception\UnexpectedValueException(sprintf('"%s" is not a valid IANA token', $calendarScale));
        }
        
        $this->calendarScale = (string) $calendarScale;
        return $this;
    }
    
    /**
     * Get calendar scale.
     * 
     * @return string
     */
    public function getCalendarScale()
    {
        return $this->calendarScale;
    }
}
