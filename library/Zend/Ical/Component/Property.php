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

/**
 * Component property
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Component
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Property
{
    /**
     * Indicator that a property is no property
     */
    const NO_PROPERTY = 0;

    /**
     * Indicator that a property is a vendor property
     */
    const VENDOR_PROPERTY = 1;

    /**
     * Property map
     *
     * @var array
     */
    protected static $_propertyMap = array(
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
        'X-*'            => array('value-types' => 'TEXT'),
        'REQUEST-STATUS' => array('value-types' => 'TEXT'),
    );
}
