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
    
    /**
     * Get timezone ID.
     * 
     * @return string
     */
    public function getTimezoneId()
    {
        $id = $this->properties()->get('TZID');
        
        if ($id !== null && $id instanceof Value\Text) {
            return $id->getText();
        }
        
        return null;
    }
    
    /**
     * Get timezone name.
     * 
     * @return string
     */
    public function getTimezoneName()
    {
        $name = $this->properties()->get('TZNAME');
        
        if ($name !== null && $name instanceof Value\Text) {
            return $name->getText();
        }
        
        return null;
    }
}
