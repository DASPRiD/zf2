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
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @namespace
 */
namespace Zend\Ical;

use Zend\Ical\Component,
    Zend\Ical\Parser\Parser;

/**
 * @category   Zend
 * @package    Zend_Ical
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Ical
{
    protected $calendars = array();

    /**
     * Add a calendar.
     *
     * @param  Component\Calendar $calendar
     * @return void
     */
    public function addCalendar(Component\Calendar $calendar)
    {
        $this->calendars[] = $calendar;
    }

    /**
     * Create an Ical object from a string.
     *
     * @param  string $string
     * @return Ical
     */
    public static function fromString($string)
    {
        return self::fromUri('data:text/calendar,' . $string);
    }

    /**
     * Create an Ical object from an URI.
     *
     * @param  string $string
     * @return Ical
     */
    public static function fromUri($uri)
    {
        $parser = new Parser(fopen($uri, 'r'));

        return $parser->parse();
    }

    /**
     * Check if a string is an IANA token.
     *
     * @param  string $string
     * @return boolean
     */
    public static function isIanaToken($string)
    {
        return (bool) preg_match('(^[A-Za-z\d\-]+$)S', $string);
    }

    /**
     * Check if a string is an X-Name.
     *
     * @param  string $string
     * @return boolean
     */
    public static function isXName($string)
    {
        return (bool) preg_match('(^[Xx]-[A-Za-z\d\-]+$)S', $string);
    }
}
