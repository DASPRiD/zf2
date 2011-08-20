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
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @namespace
 */
namespace ZendTest\Ical\Parser;

use Zend\Ical\Parser;

/**
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage UnitTests
 * @group      Zend_Ical
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParsingCalendarComponent()
    {
        $parser = $this->getParser('BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
BEGIN:VEVENT
UID:uid1@example.com
DTSTAMP:19970714T170000Z
ORGANIZER;CN=John Doe:MAILTO:john.doe@example.com
DTSTART:19970714T170000Z
DTEND:19970715T035959Z
SUMMARY:Bastille Day Party
END:VEVENT
END:VCALENDAR');

        $ical = $parser->parse();
var_dump($ical);
        // @todo: Test that calendar exists in ical object
    }

    /**
     * Get a parser instance from an ical string
     *
     * @param  string $icalString
     * @return Parser\Parser
     */
    protected function getParser($icalString)
    {
        // Correct lazyness in tests
        $icalString = rtrim(
            preg_replace('(\r?\n|\r)S', "\r\n", $icalString),
            "\r\n"
        ) . "\r\n";

        $stream = fopen('data:text/calendar,' . $icalString, 'r');

        return new Parser\Parser($stream);
    }
}
