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

/**
 * Property list.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Property
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class PropertyList
{
    /**
     * Properties.
     * 
     * @var array
     */
    protected $properties = array();
    
    /**
     * Add a property.
     * 
     * @param  Property $property 
     * @return self
     */
    public function add(Property $property)
    {
        $name = $property->getName();
        $hash = spl_object_hash($property);
        
        if (!isset($this->properties[$name])) {
            $this->properties[$name] = array();
        }
        
        $this->properties[$name][$hash] = $property;
        
        return $this;
    }
    
    /**
     * Set a property.
     * 
     * @param  Property $property
     * @return self
     */
    public function set(Property $property)
    {
        return $this->removeAll($property->getName())->add($property);
    }
    
    /**
     * Remove a single property.
     * 
     * @param  Property $property
     * @return self 
     */
    public function remove(Property $property)
    {
        $name = $propert->getName();
        $hash = spl_object_hash($property);
        
        if (isset($this->properties[$name])) {
            if (isset($this->properties[$name][$hash])) {
                unset($this->properties[$name][$hash]);
                
                if (count($this->properties[$name]) === 0) {
                    unset($this->properties[$name]);
                }
            }
        }
        
        return $this;
    }
    
    /**
     * Remove all properties of a specific name.
     * 
     * @param  string $name
     * @return self
     */
    public function removeAll($name)
    {
        if (isset($this->properties[$name])) {
            unset($this->properties[$name]);
        }
        
        return $this;
    }
    
    /**
     * Clears the list of all properties.
     * 
     * @return self
     */
    public function clear()
    {
        $this->properties = array();
        
        return $this;
    }
    
    /**
     * Get a single property of a specific name.
     * 
     * @param  string $name
     * @return Property
     */
    public function get($name)
    {
        if (isset($this->properties[$name])) {
            return reset($this->properties[$name]);
        }
        
        return null;
    }
    
    /**
     * Get all properties of a specific name.
     * 
     * @param  string $name
     * @return array
     */
    public function getAll($name)
    {
        if (isset($this->properties[$name])) {
            return $this->properties[$name];
        }
        
        return array();
    }
}
