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

use Zend\Ical\Ical,
    Zend\Ical\Property\Value\Value;

/**
 * Property.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Property
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Property
{
    /**
     * Property value types.
     * 
     * If a property supports multiple value types, the first one is the default.
     *
     * @var array
     */
    protected static $propertyNameToValueTypeMap = array(
        // Calendar properties
        'VERSION'  => array('Text'),
        'PRODID'   => array('Text'),
        'CALSCALE' => array('Text'),
        'METHOD'   => array('Text'),
        
        // Descriptive properties
        'ATTACH'           => array('Uri', 'Binary'),
        'CATEGORIES'       => array('Text'),
        'CLASS'            => array('Text'),
        'COMMENT'          => array('Text'),
        'DESCRIPTION'      => array('Text'),
        'GEO'              => array('Geo'),
        'LOCATION'         => array('Text'),
        'PERCENT-COMPLETE' => array('Integer'),
        'PRIORITY'         => array('Integer'),
        'RESOURCES'        => array('Text'),
        'STATUS'           => array('Text'),
        'SUMMARY'          => array('Text'),

        // Date and time properties
        'COMPLETED' => array('DateTime'),
        'DTEND'     => array('DateTime'),
        'DUE'       => array('DateTime'),
        'DTSTART'   => array('DateTime'),
        'DURATION'  => array('Duration'),
        'FREEBUSY'  => array('Period'),
        'TRANSP'    => array('Text'),

        // Timezone properties
        'TZID'         => array('Text'),
        'TZNAME'       => array('Text'),
        'TZOFFSETFROM' => array('UtcOffset'),
        'TZOFFSETTO'   => array('UtcOffset'),
        'TZURL'        => array('Uri'),

        // Relationship properties
        'ATTENDEE'      => array('CalAddress'),
        'CONTACT'       => array('Text'),
        'ORGANIZER'     => array('CalAddress'),
        'RECURRENCE-ID' => array('DateTime'),
        'RELATED-TO'    => array('Text'),
        'URL'           => array('Uri'),
        'UID'           => array('Text'),

        // Recurrence properties
        'EXDATE' => array('DateTime'),
        'RDATE'  => array('DateTime', 'Period'),
        'RRULE'  => array('Recurrence'),

        // Alarm properties
        'ACTION'  => array('Text'),
        'REPEAT'  => array('Integer'),
        'TRIGGER' => array('Duration', 'DateTime'),

        // Change managment properties
        'CREATED'       => array('DateTime'),
        'DTSTAMP'       => array('DateTime'),
        'LAST-MODIFIED' => array('DateTime'),
        'SEQUENCE'      => array('Integer'),

        // Miscellaneous properties
        'REQUEST-STATUS' => array('Text'),
    );
    
    /**
     * Name of the property.
     * 
     * @var string
     */
    protected $name;
    
    /**
     * Property value.
     * 
     * @var Value
     */
    protected $value;
    
    /**
     * Property parameters.
     * 
     * @var array
     */
    protected $parameters = array();
    
    /**
     * Create a new property.
     * 
     * @param  string $name 
     * @return void
     */
    public function __construct($name)
    {
        $this->name = strtoupper($name);
    }
    
    /**
     * Get the property name.
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set property value.
     * 
     * @param  Value $value
     * @return self
     */
    public function setValue(Value $value)
    {
        $this->value = $value;
        return $this;
    }
    
    /**
     * Get property value.
     * 
     * @return Value
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * Set a parameter.
     * 
     * @param  string $name
     * @param  Value  $value 
     * @return self
     */
    public function setParameter($name, Value $value)
    {
        $name = strtoupper($name);
        
        $this->parameters[$name] = $value;
        
        return $this;
    }
    
    /**
     * Remove a parameter.
     * 
     * @param  string $name
     * @return self
     */
    public function removeParameter($name)
    {
        $name = strtoupper($name);
        
        if (isset($this->parameters[$name])) {
            unset($this->parameters[$name]);
        }

        return $this;
    }
    
    /**
     * Get a parameter.
     * 
     * @param  string $name 
     * @return Value
     */
    public function getParameter($name) {
        $name = strtoupper($name);
        
        if (isset($this->parameters[$name])) {
            return $this->parameters[$name];
        }

        return null;
    }
    
    /**
     * Get value types from property name.
     * 
     * @param  string $name
     * @return array
     */
    public static function getValueTypesFromName($name)
    {
        if (!isset(self::$propertyNameToValueTypeMap[$name])) {
            return array('Raw');
        }

        return self::$propertyNameToValueTypeMap[$name];
    }
}
