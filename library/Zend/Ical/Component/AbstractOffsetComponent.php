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

use Zend\Ical\Property\Property,
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
abstract class AbstractOffsetComponent extends AbstractComponent
{
    public function setStart($dateTime)
    {
        $property = $this->properties()->get('DTSTART');
        
        if ($property === null) {
            $property = new Property('DTSTART');
            $property->setValue(new Value\DateTime($dateTime, false));
            $this->properties()->add($property);
        } elseif ($property->getValue() instanceof Value\DateTime) {
            $property->getValue()->setDateTime($dateTime, false);
        } else {
            throw new Exception\RuntimeException('Value type of DTSTART property is not DateTime');
        }
    }
    
    public function getStart()
    {
        return $this->properties()->get('DTSTART');
    }
    
    public function getOffsetFrom()
    {
        return $this->properties()->get('TZOFFSETFROM');
    }
    
    public function getOffsetTo()
    {
        return $this->properties()->get('TZOFFSETTO');
    }
}
