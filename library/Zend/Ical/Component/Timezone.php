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

use Zend\Ical\Ical,
    Zend\Ical\Exception,
    Zend\Ical\Property\Property,
    Zend\Ical\Property\Value;

/**
 * Timezone component.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Component
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Timezone extends AbstractComponent
{
    /**
     * Aliases for timezones which are not in the TZ database anymore.
     * 
     * @var array
     */
    protected static $timezoneAliases = array(
        'Asia/Katmandu'                    => 'Asia/Kathmandu',
        'Asia/Calcutta'                    => 'Asia/Kolkata',
        'Asia/Saigon'                      => 'Asia/Ho_Chi_Minh',
        'Africa/Asmera'                    => 'Africa/Asmara',
        'Africa/Timbuktu'                  => 'Africa/Bamako',
        'Atlantic/Faeroe'                  => 'Atlantic/Faroe',
        'Atlantic/Jan_Mayen'               => 'Europe/Oslo',
        'America/Argentina/ComodRivadavia' => 'America/Argentina/Catamarca',
        'America/Louisville'               => 'America/Kentucky/Louisville',
        'Europe/Belfast'                   => 'Europe/London',
        'Pacific/Yap'                      => 'Pacific/Truk',
    );
    
    /**
     * Offsets.
     * 
     * @var array
     */
    protected $offsets = array();
    
    /**
     * getName(): defined by AbstractComponent.
     * 
     * @see    AbstractComponent::getName()
     * @return string
     */
    public function getName()
    {
        return 'VTIMEZONE';
    }
    
    /**
     * Create a Timezone component from a timezone ID.
     * 
     * @param  string $timezoneId
     * @return Timezone
     */
    public static function fromTimezoneId($timezoneId)
    {
        if (isset(self::$timezoneAliases[$timezoneId])) {
            $filename = self::$timezoneAliases[$timezoneId];
        } else {
            $filename = $timezoneId;
        }
        
        $ical     = Ical::fromUri(__DIR__ . '/../Data/Timezones/' . $filename . '.ics');
        $timezone = $ical->getCalendar()->getTimezone($filename);
        
        if ($timezone->getPropertyValue('TZID') !== $timezoneId) {
            $timezone->properties()->get('TZID')->setText($timezoneId);
        }
        
        return $timezone;
    }
    
    /**
     * Add an offset to the timezone.
     * 
     * @param  AbstractOffsetComponent $offset
     * @return self
     */
    public function addOffset(AbstractOffsetComponent $offset)
    {
        $this->offsets[] = $offset;
        return $this;
    }
    
    /**
     * Set offsets.
     * 
     * $offsets must either be an instance of 'Standard' or 'Daylight', or an
     * array consiting of at least one 'Standard' and 'Daylight' component.
     * 
     * @param  mixed $offsets
     * @return self
     */
    public function setOffsets($offsets)
    {
        if ($offsets instanceof AbstractOffsetComponent) {
            $offsets = array($offsets);
        } elseif (!is_array($offsets)) {
            throw new Exception\InvalidArgumentException('Offset is no instance of AbstractOffsetComponent, nor an array');
        }
        
        $this->offsets = array();
        
        foreach ($offsets as $offset) {
            if (!$offsets instanceof AbstractOffsetComponent) {
                throw new Exception\InvalidArgumentException('Offset is no instance of AbstractOffsetComponent');
            }
            
            $this->offsets[] = $offset;
        }

        return $this;
    }
    
    /**
     * Get offsets.
     * 
     * @return array
     */
    public function getOffsets()
    {
        return $this->offsets;
    }
}
