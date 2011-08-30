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
 * @subpackage Zend_Ical_Component
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @namespace
 */
namespace Zend\Ical\Component;

use Zend\Ical\Exception,
    Zend\Ical\Property\PropertyList,
    Zend\Ical\Property\Value;

/**
 * Abstract component.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Component
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class AbstractComponent
{
    /**
     * Component types.
     */
    const COMPONENT_NONE         = 0;
    const COMPONENT_EXPERIMENTAL = 1;
    const COMPONENT_IANA         = 2;
    
    /**
     * Map of component names to componen types.
     * 
     * @var array
     */
    protected static $nameToTypeMap = array(
        'VCALENDAR' => 'Calendar',
        'VALARM'    => 'Alarm',
        'VTIMEZONE' => 'Timezone',
        'STANDARD'  => 'OffsetStandard',
        'DAYLIGHT'  => 'OffsetDaylight',
        'VEVENT'    => 'Event',
        'VTODO'     => 'Todo',
        'VJOURNAL'  => 'JournalEntry',
        'VFREEBUSY' => 'FreeBusyTime'
    );
    
    /**
     * Properties.
     * 
     * @var PropertyList
     */
    protected $properties;
        
    /**
     * Create a new component.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->properties = new PropertyList();
    }
    
    /**
     * Get all properties.
     * 
     * @return PropertyList
     */
    public function properties()
    {
        return $this->properties;
    }
    
    /**
     * Get the iCalendar conforming component name.
     * 
     * It is important that the returned name is uppercased.
     * 
     * @return string
     */
    abstract public function getName();
    
    /**
     * Get component type from name.
     * 
     * @param string $name
     */
    public static function getTypeFromName($name)
    {
        if (!isset(self::$nameToTypeMap[$name])) {
            if (Ical::isXName($name)) {
                return self::COMPONENT_EXPERIMENTAL;
            } elseif (Ical::isIanaToken($name)) {
                return self::COMPONENT_IANA;
            } else {
                return self::COMPONENT_NONE;
            }
        }

        return self::$nameToTypeMap[$name];
    }
    
    /**
     * Get the value of a single instance property.
     * 
     * @param  string $name
     * @return mixed
     */
    public function getPropertyValue($name)
    {
        $property = $this->properties()->get($name);
        
        if ($property === null) {
            return null;
        }
        
        $value = $property->getValue();
        
        if ($value instanceof Value\Text) {
            return $value->getText();
        }
        
        return null;
    }
}
