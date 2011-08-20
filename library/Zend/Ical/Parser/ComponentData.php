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

use Zend\Ical\Component\AbstractComponent;

/**
 * Component data container.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Parser
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ComponentData
{
    /**
     * Component type.
     * 
     * @var string
     */
    protected $type;
    
    /**
     * Component name.
     * 
     * @var string
     */
    protected $name;
    
    /**
     * Properties.
     * 
     * @var array
     */
    protected $properties = array();
    
    /**
     * Components.
     * 
     * @var array
     */
    protected $components = array();
    
    /**
     * Create a new component data container.
     * 
     * @param  string $type
     * @param  string $name
     * @return void
     */
    public function __construct($type, $name)
    {
        $this->type = $type;
        $this->name = $name;
    }
    
    /**
     * Get the component type.
     * 
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * Get the component name.
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Add a property.
     * 
     * @param  string $name
     * @param  array  $property
     * @return void
     */
    public function addProperty($name, array $property)
    {
        $name = strtoupper($name);
        
        if (!isset($this->properties[$name])) {
            $this->properties[$name] = array();
        }
        
        $this->properties[$name][] = $property;
    }
    
    /**
     * Get all properties.
     * 
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }
    
    /**
     * Add a component.
     * 
     * @param  string            $type
     * @param  AbstractComponent $component
     * @return void
     */
    public function addComponent($type, AbstractComponent $component)
    {       
        if (!isset($this->components[$type])) {
            $this->components[$type] = array();
        }
        
        $this->components[$type][] = $component;
    }
    
    /**
     * Get all components.
     * 
     * @return array
     */
    public function getComponents()
    {
        return $this->components;
    }
}
