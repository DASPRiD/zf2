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
 * @subpackage Zend_Ical_Parser
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @namespace
 */
namespace Zend\Ical\Parser;

/**
 * Parser mapping.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Parser
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Mapping
{
    /**
     * Special types.
     */
    const NONE = 0;
    const X    = 1;
    const IANA = 2;
    
    /**
     * Component types.
     *
     * @var array
     */
    protected $componentTypes = array(
        'VCALENDAR' => array(
            'type'                => 'Calendar',
            'required-properties' => array('VERSION', 'PRODID'),
            'required-components' => array(),
            'components'          => array(
                'Event',
                'Freebusy',
                'Journal',
                'Todo',
                'Timezone',
                self::X,
                self::IANA,
            ),
            'properties'          => array(
                'VERSION'  => array('multiple' => false),
                'PRODID'   => array('multiple' => false),
                'CALSCALE' => array('multiple' => false),
                'METHOD'   => array('multiple' => false),
            ),
        ),
        'VALARM' => array(
            'type' => 'Alarm',
            'required-properties' => array('ACTION', 'TRIGGER'),
            'required-components' => array(),
            'components'          => array(),
            'properties'          => array(
                'ACTION'    => array('multiple' => false),
                'TRIGGER'   => array('multiple' => false),
                'REPEAT'    => array('multiple' => false),
                'DURATION'  => array('multiple' => false),
                'ATTACH'    => array('multiple' => false),
                self::X     => array('multiple' => true),
            )
        ),
        'VEVENT'    => array(
            'type'                => 'Event',
            'required-properties' => array(),
            'required-components' => array(),
            'components'          => array('Alarm'),
            'properties'          => array(
                'CLASS'       => array('multiple' => false),
                'CREATED'     => array('multiple' => false),
                'DESCRIPTION' => array('multiple' => false),
                'DTSTART'     => array('multiple' => false),
                'GEO'         => array('multiple' => false),
                'LAST-MOD'    => array('multiple' => false),
                'LOCATION'    => array('multiple' => false),
                'ORGANIZER'   => array('multiple' => false),
                'PRIORITY'    => array('multiple' => false),
                'DTSTAMP'     => array('multiple' => false),
                'SEQ'         => array('multiple' => false),
                'STATUS'      => array('multiple' => false),
                'SUMMARY'     => array('multiple' => false),
                'TRANSP'      => array('multiple' => false),
                'UID'         => array('multiple' => false),
                'URL'         => array('multiple' => false),
                'RECURID'     => array('multiple' => false),
                'DTEND'       => array('multiple' => false),
                'DURATION'    => array('multiple' => false),
                'ATTACH'      => array('multiple' => true),
                'ATTENDEE'    => array('multiple' => true),
                'CATEGORIES'  => array('multiple' => true),
                'COMMENT'     => array('multiple' => true),
                'CONTACT'     => array('multiple' => true),
                'EXDATE'      => array('multiple' => true),
                'EXRULE'      => array('multiple' => true),
                'RSTATUS'     => array('multiple' => true),
                'RELATED'     => array('multiple' => true),
                'RESOURCES'   => array('multiple' => true),
                'RDATE'       => array('multiple' => true),
                'RRULE'       => array('multiple' => true),
                self::X       => array('multiple' => true),
            )
        ),
        'VFREEBUSY' => 'FreeBusy',
        'VJOURNAL'  => 'Journal',
        'VTODO'     => 'Todo',
        'VTIMEZONE' => array(
            'type' => 'Timezone',
            'required-properties' => array('TZID'),
            'required-components' => array(),
            'components'          => array('Standard', 'Daylight'),
            'properties'          => array(
                'TZID'     => array('multiple' => false),
                'LAST-MOD' => array('multiple' => false),
                self::X    => array('multiple' => true),
            )
        ),
        'STANDARD' => array(
            'type' => 'Standard',
            'required-properties' => array('DTSTART', 'TZOFFSETTO', 'TZOFFSETFROM'),
            'required-components' => array(),
            'components'          => array(),
            'properties'          => array(
                'DTSTART'      => array('multiple' => false),
                'TZOFFSETTO'   => array('multiple' => false),
                'TZOFFSETFROM' => array('multiple' => false),
                'COMMENT'      => array('multiple' => true),
                'RDATE'        => array('multiple' => true),
                'RRULE'        => array('multiple' => true),
                'TZNAME'       => array('multiple' => true),
                self::X        => array('multiple' => true),
            )
        ),
        'DAYLIGHT' => array(
            'type' => 'Daylight',
            'required-properties' => array('DTSTART', 'TZOFFSETTO', 'TZOFFSETFROM'),
            'required-components' => array(),
            'components'          => array(),
            'properties'          => array(
                'DTSTART'      => array('multiple' => false),
                'TZOFFSETTO'   => array('multiple' => false),
                'TZOFFSETFROM' => array('multiple' => false),
                'COMMENT'      => array('multiple' => true),
                'RDATE'        => array('multiple' => true),
                'RRULE'        => array('multiple' => true),
                'TZNAME'       => array('multiple' => true),
                self::X        => array('multiple' => true),
            )
        )
    );
    
    /**
     * Property types.
     *
     * @var array
     */
    protected $propertyTypes = array(
        // Calendar properties
        'VERSION'  => array('value-types' => 'VERSION'),
        'PRODID'   => array('value-types' => 'TEXT'),
        'CALSCALE' => array('value-types' => 'CALSCALE'),
        'METHOD'   => array('value-types' => 'TEXT'),
        
        // Descriptive properties
        'ATTACH'           => array('value-types' => array('URI', 'BINARY')),
        'CATEGORIES'       => array('value-types' => 'TEXT'),
        'CLASS'            => array('value-types' => 'CLASS'),
        'COMMENT'          => array('value-types' => 'TEXT'),
        'DESCRIPTION'      => array('value-types' => 'TEXT'),
        'GEO'              => array('value-types' => 'GEO'),
        'LOCATION'         => array('value-types' => 'TEXT'),
        'PERCENT-COMPLETE' => array('value-types' => 'INTEGER'),
        'PRIORITY'         => array('value-types' => 'INTEGER'),
        'RESOURCES'        => array('value-types' => 'TEXT'),
        'STATUS'           => array('value-types' => 'TEXT'),
        'SUMMARY'          => array('value-types' => 'TEXT'),

        // Date and time properties
        'COMPLETED' => array('value-types' => 'DATE-TIME'),
        'DTEND'     => array('value-types' => array('DATE-TIME', 'DATE')),
        'DUE'       => array('value-types' => array('DATE-TIME', 'DATE')),
        'DTSTART'   => array('value-types' => array('DATE-TIME', 'DATE')),
        'DURATION'  => array('value-types' => 'DURATION'),
        'FREEBUSY'  => array('value-types' => 'PERIOD'),
        'TRANSP'    => array('value-types' => 'TEXT'),

        // Timezone properties
        'TZID'         => array('value-types' => 'TEXT'),
        'TZNAME'       => array('value-types' => 'TEXT'),
        'TZOFFSETFROM' => array('value-types' => 'UTC-OFFSET'),
        'TZOFFSETTO'   => array('value-types' => 'UTC-OFFSET'),
        'TZURL'        => array('value-types' => 'URI'),

        // Relationship properties
        'ATTENDEE'      => array('value-types' => 'CAL-ADDRESS'),
        'CONTACT'       => array('value-types' => 'TEXT'),
        'ORGANIZER'     => array('value-types' => 'CAL-ADDRESS'),
        'RECURRENCE-ID' => array('value-types' => 'DATE-TIME'),
        'RELATED-TO'    => array('value-types' => 'TEXT'),
        'URL'           => array('value-types' => 'URI'),
        'UID'           => array('value-types' => 'TEXT'),

        // Recurrence properties
        'EXDATE' => array('value-types' => array('DATE-TIME', 'DATE')),
        'EXRULE' => array('value-types' => 'RECUR'),
        'RDATE'  => array('value-types' => array('DATE-TIME', 'DATE', 'PERIOD')),
        'RRULE'  => array('value-types' => 'RECUR'),

        // Alarm properties
        'ACTION'  => array('value-types' => 'TEXT'),
        'REPEAT'  => array('value-types' => 'INTEGER'),
        'TRIGGER' => array('value-types' => array('DURATION', 'DATE-TIME')),

        // Change managment properties
        'CREATED'       => array('value-types' => 'DATE-TIME'),
        'DTSTAMP'       => array('value-types' => 'DATE-TIME'),
        'LAST-MODIFIED' => array('value-types' => 'DATE-TIME'),
        'SEQUENCE'      => array('value-types' => 'INTEGER'),

        // Miscellaneous properties
        '*X'             => array('value-types' => 'TEXT'),
        '*IANA'          => array('value-types' => 'TEXT'),
        'REQUEST-STATUS' => array('value-types' => 'TEXT'),
    );
    
    /**
     * Get the component type for a component.
     *
     * @param  string $component
     * @return string
     */
    public function getComponentType($component)
    {
        $component = strtoupper($component);

        if (!isset($this->componentTypes[$component])) {
            if (Ical::isXName($component)) {
                return self::X;
            } elseif (Ical::isIanaToken($component)) {
                return self::IANA;
            } else {
                return self::NONE;
            }
        }

        return $this->componentTypes[$component]['type'];
    }
    
    /**
     * Get all allowed components for a specific component.
     * 
     * @param  ComponentData $component 
     * @return array
     */
    public function getAllowedComponents(ComponentData $component)
    {
        $type = $component->getType();
        
        if ($type === self::X || $type === self::IANA) {
            return '*';
        } else {
            return $this->componentTypes[$component->getName()]['components'];
        }
    }
    
    /**
     * Get component definitions.
     * 
     * @param  ComponentData $component 
     * @return array
     */
    public function getComponentDefinitions(ComponentData $component)
    {
        $type = $component->getType();
        
        if ($type === self::X || $type === self::IANA) {
            return '*';
        } else {
            return $this->componentTypes[$component->getName()];
        }
    }
    
    /**
     * Get property definitions.
     * 
     * @param  string $property
     * @return array
     */
    public function getPropertyDefinitions($property)
    {
        $property = strtoupper($property);

        if (!isset($this->propertyTypes[$property])) {
            if (Ical::isXName($property)) {
                $property = '*X';
            } elseif (Ical::isIanaToken($property)) {
                $property = '*IANA';
            } else {
                return self::NONE;
            }
        }

        return $this->propertyTypes[$property];
    }
}
