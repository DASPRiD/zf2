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
namespace ZendTest\Ical\Property\Value;

use Zend\Ical\Property\Value;

/**
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage UnitTests
 * @group      Zend_Ical
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class DateTest extends \PHPUnit_Framework_TestCase
{/*
    public function testWeekdayCalculation()
    {
        $date = new Value\DateTime(2011, 8, 26);
        $this->assertEquals(6, $date->getWeekday());
        
        $date = new Value\DateTime(1960, 1, 1);
        $this->assertEquals(6, $date->getWeekday());
        
        $date = new Value\DateTime(1582, 10, 15);
        $this->assertEquals(6, $date->getWeekday());
        
        $date = new Value\DateTime(1582, 10, 15);
        $this->assertEquals(6, $date->getWeekday());
        
        $date = new Value\DateTime(1582, 10, 14);
        $this->assertEquals(1, $date->getWeekday());
    }
    
    public function testDayOfYearCalculation()
    {
        $date = new Value\DateTime(2011, 8, 26);
        $this->assertEquals(238, $date->getDayOfYear());
        
        $date = new Value\DateTime(2011, 1, 1);
        $this->assertEquals(1, $date->getDayOfYear());
    }*/
    
    public function testWeekNoCalculation()
    {
        /*
        // Weeks starting with Sunday
        $date = new Value\DateTime(2011, 1, 1);
        $this->assertEquals(0, $date->getWeekNo());
        
        $date = new Value\DateTime(2011, 1, 2);
        $this->assertEquals(1, $date->getWeekNo());
        
        $date = new Value\DateTime(2011, 1, 9);
        $this->assertEquals(2, $date->getWeekNo());
        
        // Weeks starting with Monday
        $date = new Value\DateTime(2011, 1, 1);
        $this->assertEquals(0, $date->getWeekNo(2));
        
        $date = new Value\DateTime(2011, 1, 2);
        $this->assertEquals(0, $date->getWeekNo(2));
        */
        $date = new Value\DateTime(2011, 1, 3);
        $this->assertEquals(1, $date->getWeekNo(2));
        
        $date = new Value\DateTime(2011, 1, 9);
        $this->assertEquals(1, $date->getWeekNo(2));

        $date = new Value\DateTime(2011, 1, 10);
        $this->assertEquals(2, $date->getWeekNo(2));
    }
}
