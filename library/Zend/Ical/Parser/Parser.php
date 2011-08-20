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

use Zend\Ical\Ical,
    Zend\Ical\Exception,
    Zend\Ical\Component,
    Zend\Ical\Property;

/**
 * Ical parser based on libical.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Parser
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Parser
{
    /**
     * Stream resource.
     *
     * @var resource
     */
    protected $stream;

    /**
     * Buffer for getting folded lines.
     *
     * @var string
     */
    protected $buffer;

    /**
     * Raw data of the current line.
     *
     * @var string
     */
    protected $rawData;

    /**
     * Current position in the current line.
     *
     * @var integer
     */
    protected $currentPos;

    /**
     * Ical object.
     * 
     * @var Ical
     */
    protected $ical;

    /**
     * Regular expressions used in the parser.
     * 
     * @var array
     */
    protected $regex;
    
    /**
     * Mapping table.
     * 
     * @var Mapping
     */
    protected $mapping;

    /**
     * Data stack.
     *
     * @var \SplStack
     */
    protected $data;
    
    /**
     * Component stack.
     *
     * @var \SplStack
     */
    protected $components;
    
    /**
     * Create a new lexer with an open file stream.
     *
     * @param  resource $stream
     * @return void
     */
    public function __construct($stream)
    {
        if (!is_resource($stream)) {
            throw new Exception\InvalidArgumentException('Stream must be a resource');
        }

        $this->stream = $stream;
        
        // Mapping table
        $this->mapping = new Mapping();

        // Base regex types
        $this->regex = array(
            'iana-token'   => '[A-Za-z\d\-]+',
            'x-name'       => '[Xx]-[A-Za-z\d]{3,}-[A-Za-z\d\-]+',
            'safe-char'    => '[\x20\x09\x21\x23-\x2B\x2D-\x39\x3C-\x7E\x80-\xFB]',
            'qsafe-char'   => '[\x20\x09\x21\x23-\x7E\x80-\xFB]',
            'tsafe-char'   => '[\x20\x21\x23-\x2B\x2D-\x39\x3C-\x5B\x5D-\x7E\x80-\xFB]',
            'value-char'   => '[\x20\x09\x21-\x7E\x80-\xFB]',
            'escaped-char' => '(?:\\\\|\\;|\\,|\\\\N|\\\\n)'
        );

        // Regex types based on base type
        $this->regex['param-text']    = '(' . $this->regex['safe-char'] . '*)';
        $this->regex['quoted-string'] = '"(' . $this->regex['qsafe-char'] . '*)"';
        $this->regex['name']          = '(?:'. $this->regex['x-name'] . '|' . $this->regex['iana-token'] . ')';
        $this->regex['param-name']    = '(?:('. $this->regex['x-name'] . ')|(' . $this->regex['iana-token'] . '))';
        $this->regex['param-value']   = '(?:'. $this->regex['quoted-string'] . '|' . $this->regex['param-text'] . ')';
        $this->regex['text']          = '((?:' . $this->regex['tsafe-char'] . '|' . $this->regex['escaped-char'] . '|[:"])*)';
        $this->regex['value']         = $this->regex['value-char'] . '*';
    }

    /**
     * Parse the input from the stream.
     *
     * @return Ical
     */
    public function parse()
    {
        $this->ical       = new Ical();
        $this->data       = new \SplStack();
        $this->components = new \SplStack();

        while (($this->rawData = $this->getNextUnfoldedLine()) !== null) {
            $this->parseLine();
        }

        if (count($this->components) > 0) {
            throw new Exception\ParseException('Unexpected end in input stream');
        }

        return $this->ical;
    }

    /**
     * Parse a single line.
     *
     * @return void
     */
    protected function parseLine()
    {
        $this->currentPos = 0;
        $propertyName     = $this->getPropertyName();

        if ($propertyName === null) {
            throw new Exception\ParseException('Could not find a property name, component begin or end tag');
        }

        // If the property name is BEGIN or END, we are actually starting or
        // ending a new component.
        if (strcasecmp($propertyName, 'BEGIN') === 0) {
            return $this->handleComponentBegin();
        } elseif (strcasecmp($propertyName, 'END') === 0) {
            return $this->handleComponentEnd();
        }

        // At this point, the property name really is a property name (Not a
        // component name), so make a new property and add it to the component.
        if (count($this->data) === 0) {
            throw new Exception\ParseException('Found property name within root');
        }
         
        $property = array(
            'parameters' => array(),
            'values'     => array()
        );

        // Handle parameter values
        while ($this->rawData[$this->currentPos - 1] !== ':') {
            $parameterName = $this->getNextParameterName();

            if ($parameterName === null) {
                throw new Exception\ParseException('Could not find a parameter name');
            }

            $parameterValue = $this->getNextParameterValue();

            if ($parameterValue === null) {
                throw new Exception\ParseException('Could not find a parameter value');
            }

            $property['parameters'][$parameterName] = $parameterValue;
        }
        
        // Handle property values
        do {
            $property['values'][] = $this->getNextValue();
        } while ($this->rawData[$this->currentPos - 1] === ',');

        $this->data->top()->addProperty($propertyName, $property);
    }

    /**
     * Handle the beginning of a component.
     *
     * @return void
     */
    protected function handleComponentBegin()
    {
        $componentName = $this->getNextValue();
        $componentType = $this->mapping->getComponentType($componentName);

        if ($componentType === Mapping::NONE) {
            throw new ParseException(sprintf('Invalid component name "%s"', $componentName));
        }

        if ($componentType === 'Calendar') {
            if (count($this->data) > 0) {
                throw new Exception\ParseException('Calendar component found outside of root');
            }
        } else {
            if (count($this->data) === 0) {
                throw new Exception\ParseException('Component found inside root');
            } else {
                $allowedComponents = $this->mapping->getAllowedComponents($this->data->top());
                
                if ($allowedComponents !== '*' && !in_array($componentType, $allowedComponents)) {
                    if ($componentType === Mapping::X) {
                        throw new Exception\ParseException(sprintf('Vendor component found inside %s component', $componentType, $this->stack->top()->getType()));
                    } elseif ($componentType === Mapping::IANA) {
                        throw new Exception\ParseException(sprintf('IANA component found inside %s component', $componentType, $this->stack->top()->getType()));
                    } else {
                        throw new Exception\ParseException(sprintf('%s component found inside %s component', $componentType, $this->stack->top()->getType()));
                    }
                }
            }
        }
        
        $this->data->push(new ComponentData($componentType, $componentName));
    }

    /**
     * Handle the ending of a component.
     *
     * @return void
     */
    protected function handleComponentEnd()
    {
        $componentName = $this->getNextValue();
        $componentType = $this->mapping->getComponentType($componentName);
        $componentData = $this->data->pop();

        if ($componentType === Mapping::NONE) {
            throw new Exception\ParseException(sprintf('Invalid component name "%s"', $componentName));
        } elseif ($componentType !== $componentData->getType()) {
            throw new Exception\ParseException(sprintf('Ending tag does not match current component'));
        }
        
        $component = $this->createComponent($componentData);
        
        if ($componentType === 'Calendar') {
            $this->ical->addCalendar($component);
        } elseif ($componentType) {
            $this->data->top()->addComponent($componentType, $component);
        }
    }
    
    /**
     * Get a property name.
     *
     * @return string
     */
    protected function getPropertyName()
    {
        if (!preg_match(
            '(\G(?<name>' . $this->regex['name'] . ')[;:])S',
            $this->rawData, $match, 0, $this->currentPos
        )) {
            return null;
        }

        $this->currentPos += strlen($match[0]);

        return $match['name'];
    }

    /**
     * Get the next value of a property.
     *
     * A property may have multiple values, if the values are separated by
     * commas in the content line.
     *
     * @param  string $kind
     * @return string
     */
    protected function getNextValue($kind = null)
    {
        if (!preg_match(
            '(\G(?<value>' . $this->regex['value'] . ')(?<sep>,|\r\n))S',
            $this->rawData, $match, 0, $this->currentPos
        )) {
            return null;
        }

        $this->currentPos += strlen($match[0]);

        return $match['value'];
    }

    /**
     * Get the next parameter name.
     *
     * @return string
     */
    protected function getNextParameterName()
    {
        if (!preg_match(
            '(\G(?<name>' . $this->regex['name'] . ')=)S',
            $this->rawData, $match, 0, $this->currentPos
        )) {
            return null;
        }

        $this->currentPos += strlen($match[0]);

        return $match['name'];
    }
    
    /**
     * Get the next parameter value.
     *
     * @return string
     */
    protected function getNextParameterValue()
    {
        if (!preg_match(
            '(\G(?<value>' . $this->regex['param-value'] . ')[,:])S',
            $this->rawData, $match, 0, $this->currentPos
        )) {
            return null;
        }

        $this->currentPos += strlen($match[0]);

        return $match['value'];
    }

    /**
     * Get the next unfolded line from the stream.
     *
     * @return string
     */
    protected function getNextUnfoldedLine()
    {
        if (feof($this->stream)) {
            return null;
        }

        $rawData = $this->buffer . fgets($this->stream);

        while (
            !feof($this->stream) && ($this->buffer = fgetc($this->stream))
            && ($this->buffer === ' ' || $this->buffer === "\t")
        ) {
            $rawData      = rtrim($rawData, "\r\n") . fgets($this->stream);
            $this->buffer = '';
        }

        return $rawData;
    }
    
    /**
     * Create a new component.
     * 
     * @param  ComponentData $data 
     * @return Component\AbstractComponent
     */
    public function createComponent(ComponentData $data)
    {
        $type = $data->getType();
        
        if ($type === Mapping::X) {
            $component = new Component\Vendor($data->getName());
        } elseif ($type === Mapping::IANA) {
            $component = new Component\Iana($data->getName());
        } elseif ($type === 'Timezone') {
            $component = new Component\Timezone('tzid and stuffz');
        } else {
            $className = '\Zend\Ical\Component\\' . $type;
            $component = new $className();
        }
        
        $this->fillComponentWithComponents($component, $data);
        $this->fillComponentWithProperties($component, $data);
        
        return $component;
    }
    
    /**
     * Fill a component with sub-components.
     * 
     * @param  Component\AbstractComponent $component
     * @param  ComponentData               $data
     * @return void
     */
    protected function fillComponentWithComponents(Component\AbstractComponent $component, ComponentData $data)
    {
        foreach ($data->getComponents() as $componentType => $subComponents) {
            if ($componentType === Mapping::IANA) {
                foreach ($subComponents as $subComponent) {
                    $component->addIanaComponent($subComponent);
                }
            } elseif ($componentType === Mapping::X) {
                foreach ($subComponents as $subComponent) {
                    $component->addVendorComponent($subComponent);
                }
            } elseif ($componentType === 'Standard' || $componentType === 'Daylight') {
                foreach ($subComponents as $subComponent) {
                    $component->addOffset($subComponent);
                }
            } else {
                foreach ($subComponents as $subComponent) {
                    $component->{'add' . $componentType}($subComponent);
                }
            }
        }
    }
    
    /**
     * Fill a component with properties.
     * 
     * @param  Component\AbstractComponent $component
     * @param  ComponentData               $data
     * @return void
     */
    protected function fillComponentWithProperties(Component\AbstractComponent $component, ComponentData $data)
    {   
        $componentDefinitions = $this->mapping->getComponentDefinitions($data);
        $properties           = $data->getProperties();
        
        foreach ($componentDefinitions['required-properties'] as $requiredProperty) {
            if (!isset($properties[$requiredProperty])) {
                throw new Exception\ParseException(sprintf('Property %s missing in %s', $propertyName, $data->getName()));
            }
        }
        
        foreach ($properties as $propertyName => $properties) {
            $definitions = $this->mapping->getPropertyDefinitions($propertyName);
            
            if (!isset($componentDefinitions['properties'][$propertyName])) {
                throw new Exception\ParseException(sprintf('Property %s is not allowed within %s', $propertyName, $data->getName()));
            } elseif (!$componentDefinitions['properties'][$propertyName]['multiple'] && count($properties) > 1) {
                throw new Exception\ParseException(sprintf('Property %s may not occur more than once in %s', $propertyName, $data->getName()));
            }
            
            foreach ($properties as $propertyData) {
                $property = $this->createProperty($propertyName, $propertyData, $definitions);
                
                $component->{'set' . $definitions['type']}($property);
            }
        }
    }
    
    /**
     * Create a new property.
     * 
     * @param  string  $name
     * @param  array   $data
     * @param  array   $definitions
     * @return Property\PropertyAbstract
     */
    protected function createProperty($name, array $data, array $definitions)
    {
        // Validate data
        $standardParameters = array();
        $vendorParameters   = array();
        
        // Check standard parameters
        if (isset($definitions['parameters'])) {
            foreach ($definitions['parameters'] as $parameterName) {
                if (isset($data['parameters'][$parameterName])) {
                    $parameters[$parameterName] = $data['parameters'][$parameterName];
                    unset($data['parameters'][$parameterName]);
                }
            }
        }
        
        // Check remaining parameters
        foreach ($data['parameters'] as $parameterName => $parameterValue) {
            if (!Ical::isXName($parameterName)) {
                throw new Exception\ParseException(sprintf('Parameter name %s is not valid', $parameterName));
            } else {
                $vendorParameters[$parameterName] = $parameterValue;
            }
        }
        
        $className = '\Zend\Ical\Property\\' . $definitions['type'];
        $property  = new $className($data['values'][0]);
        
        foreach ($standardParameters as $parameterName => $parameterValue) {
            //$property->{'set' . } addVendorParameter($parameterName, $parameterValue);
        }
        
        foreach ($vendorParameters as $parameterName => $parameterValue) {
            $property->addVendorParameter($parameterName, $parameterValue);
        }
               
        return $property;
    }
}
