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
 * @subpackage Zend_Ical_Specs
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @namespace
 */
namespace Zend\Ical\Specs;

/**
 * Specs for all properties
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Specs
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Properties
{
    /**
     * Indicator that a property is no property
     */
    const NO_PROPERTY = 0;

    /**
     * Indicator that a property is a vendor property
     */
    const X_PROPERTY = 1;

    /**
     * Property map
     *
     * @var array
     */
    protected static $_propertyMap = array(
        array('name' => 'ACTION',           'type' => 'ACTION',           'default' => 'ACTION'),
        array('name' => 'ALLOW-CONFLICT',   'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'ATTACH',           'type' => 'ATTACH',           'default' => 'URI'),
        array('name' => 'ATTENDEE',         'type' => 'CAL-ADDRESS',      'default' => 'CAL-ADDRESS'),
        array('name' => 'CALSCALE',         'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'CATEGORIES',       'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'CALID',            'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'CARID',            'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'CLASS',            'type' => 'CLASS',            'default' => 'CLASS'),
        array('name' => 'COMMENT',          'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'COMPLETED',        'type' => 'DATE-TIME',        'default' => 'DATE-TIME'),
        array('name' => 'CONTACT',          'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'CREATED',          'type' => 'DATE-TIME',        'default' => 'DATE-TIME'),
        array('name' => 'DECREED',          'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'DEFAULT-CHARSET',  'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'DEFAULT-LOCALE',   'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'DEFAULT-TZID',     'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'DESCRIPTION',      'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'DTEND',            'type' => 'DATE-TIME',        'default' => 'DATE-TIME'),
        array('name' => 'DTSTAMP',          'type' => 'DATE-TIME',        'default' => 'DATE-TIME'),
        array('name' => 'DTSTART',          'type' => 'DATE-TIME',        'default' => 'DATE-TIME'),
        array('name' => 'DUE',              'type' => 'DATE-TIME',        'default' => 'DATE-TIME'),
        array('name' => 'DURATION',         'type' => 'DURATION',         'default' => 'DURATION'),
        array('name' => 'EXDATE',           'type' => 'DATE-TIME',        'default' => 'DATE-TIME'),
        array('name' => 'EXRULE',           'type' => 'RECUR',            'default' => 'RECUR'),
        array('name' => 'FREEBUSY',         'type' => 'PERIOD',           'default' => 'PERIOD'),
        array('name' => 'GEO',              'type' => 'GEO',              'default' => 'GEO'),
        array('name' => 'LAST-MODIFIED',    'type' => 'DATE-TIME',        'default' => 'DATE-TIME'),
        array('name' => 'LOCATION',         'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'METHOD',           'type' => 'METHOD',           'default' => 'METHOD'),
        array('name' => 'ORGANIZER',        'type' => 'CAL-ADDRESS',      'default' => 'CAL-ADDRESS'),
        array('name' => 'OWNER',            'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'PERCENT-COMPLETE', 'type' => 'INTEGER',          'default' => 'INTEGER'),
        array('name' => 'PRIORITY',         'type' => 'INTEGER',          'default' => 'INTEGER'),
        array('name' => 'PRODID',           'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'RDATE',            'type' => 'DATE-TIME-PERIOD', 'default' => 'DATE-TIME'),
        array('name' => 'RECURRENCE-ID',    'type' => 'DATE-TIME',        'default' => 'DATE-TIME'),
        array('name' => 'RELATED-TO',       'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'RELCALID',         'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'REPEAT',           'type' => 'INTEGER',          'default' => 'INTEGER'),
        array('name' => 'REQUEST-STATUS',   'type' => 'REQUEST-STATUS',   'default' => 'REQUEST-STATUS'),
        array('name' => 'RESOURCES',        'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'RRULE',            'type' => 'RECUR',            'default' => 'RECUR'),
        array('name' => 'SEQUENCE',         'type' => 'INTEGER',          'default' => 'INTEGER'),
        array('name' => 'STATUS',           'type' => 'STATUS',           'default' => 'STATUS'),
        array('name' => 'SUMMARY',          'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'TRANSP',           'type' => 'TRANSP',           'default' => 'TRANSP'),
        array('name' => 'TRIGGER',          'type' => 'TRIGGER',          'default' => 'DURATION'),
        array('name' => 'TZID',             'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'TZNAME',           'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'TZOFFSETFROM',     'type' => 'UTC-OFFSET',       'default' => 'UTC-OFFSET'),
        array('name' => 'TZOFFSETTO',       'type' => 'UTC-OFFSET',       'default' => 'UTC-OFFSET'),
        array('name' => 'TZURL',            'type' => 'URI',              'default' => 'URI'),
        array('name' => 'UID',              'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'URL',              'type' => 'URI',              'default' => 'URI'),
        array('name' => 'VERSION',          'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'X',                'type' => 'X',                'default' => 'X'),
        array('name' => 'SCOPE',            'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'MAXRESULTS',       'type' => 'INTEGER',          'default' => 'INTEGER'),
        array('name' => 'MAXRESULTSSIZE',   'type' => 'INTEGER',          'default' => 'INTEGER'),
        array('name' => 'QUERY',            'type' => 'QUERY',            'default' => 'QUERY'),
        array('name' => 'QUERYNAME',        'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'EXPAND',           'type' => 'INTEGER',          'default' => 'INTEGER'),
        array('name' => 'TARGET',           'type' => 'CAL-ADDRESS',      'default' => 'CAL-ADDRESS'),
        array('name' => 'CALMASTER',        'type' => 'TEXT',             'default' => 'TEXT'),
        array('name' => 'X-LIC-CLASS',      'type' => 'X-LIC-CLASS',      'default' => 'X-LIC-CLASS'),
        array('name' => 'ANY',              'type' => 'NO',               'default' => 'NO'),
        array('name' => 'NO',               'type' => 'NO',               'default' => 'NO')
    );

    /**
     * Get the type for a property name
     *
     * @param  string $name
     * @return string
     */
    public static function nameToType($name)
    {
        foreach (self::$_propertyMap as $property) {
            if (strcasecmp($property['name'], $name) === 0) {
                return $property['type'];
            }
        }

        if (preg_match('(^[Xx]-[A-Za-z\d]{3,}-[A-Za-z\d\-]+$)S', $name)) {
            return self::X_PROPERTY;
        }

        return self::NO_PROPERTY;
    }
}
