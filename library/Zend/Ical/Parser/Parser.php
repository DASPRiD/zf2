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
    Zend\Ical\Component,
    Zend\Ical\Property\Property;

/**
 * Ical parser based on libical
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
     * Special component and property types
     */
    const NO_COMPONENT = 0;
    const X_COMPONENT  = 1;
    const NO_PROPERTY  = 1;

    /**
     * Stream resource
     *
     * @var resource
     */
    protected $stream;

    /**
     * Buffer for getting folded lines
     *
     * @var string
     */
    protected $buffer;

    /**
     * Raw data of the current line
     *
     * @var string
     */
    protected $rawData;

    /**
     * Current position in the current line
     *
     * @var integer
     */
    protected $currentPos;

    /**
     * Ical object
     * 
     * @var Ical
     */
    protected $ical;

    /**
     * Regular expressions used in the parser
     * 
     * @var array
     */
    protected $regex;

    /**
     * Component stack
     *
     * @var array
     */
    protected $components;

    /**
     * Component type map
     *
     * @var array
     */
    protected $componentTypes = array(
        'VCALENDAR' => 'Calendar',
        'VALARM'    => 'Alarm',
        'VEVENT'    => 'Event',
        'VFREEBUSY' => 'FreeBusy',
        'VJOURNAL'  => 'Journal',
        'VTODO'     => 'Todo',
        'VTIMEZONE' => 'Timezone'
    );

    /**
     * Create a new lexer with an open file stream.
     *
     * @param  resource $stream
     * @return void
     */
    public function __construct($stream)
    {
        if (!is_resource($stream)) {
            throw new InvalidArgumentException('Stream must be a resource');
        }

        $this->stream = $stream;

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
     * Parse the input from the stream
     *
     * @return Ical
     */
    public function parse()
    {
        $this->ical       = new Ical();
        $this->components = new \SplStack();

        while (($this->rawData = $this->getNextUnfoldedLine()) !== null) {
            $this->parseLine();
        }

        if (count($this->components) > 0) {
            throw new ParseException('Unexpected end in input stream');
        }

        return $this->ical;
    }

    /**
     * Parse a single line
     *
     * @return void
     */
    protected function parseLine()
    {
        $this->currentPos = 0;
        $propertyName     = $this->getPropertyName();

        if ($propertyName === null) {
            throw new ParseException('Could not find a property name, component begin or end tag');
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
        if (count($this->components) === 0) {
            throw new ParseException('Found property name within root');
        }

        $propertyType = Property::getDefaultValueType($propertyName);

        if ($propertyType === Property::NO_PROPERTY) {
            throw new ParseException('Invalid property name');
        }

        $property = new Property($propertyType);

        // Handle parameter values
        while ($this->rawData[$this->currentPos-1] !== ':') {
            $parameterName = $this->getNextParameterName();

            if ($parameterName === null) {
                throw new ParseException('Could not find a parameter name');
            }

            $parameterValue = $this->getNextParameterValue();

            if ($parameterValue === null) {
                throw new ParseException('Could not find a parameter value');
            }

            $parameter = new Parameter($parameterName, $parameterValue);
            $property->addParameter($parameter);
        }

        $this->_components->top()->addProperty($property);
    }

    /**
     * Handle the beginning of a component
     *
     * @return void
     */
    protected function handleComponentBegin()
    {
        $componentName = $this->getNextValue();
        $componentType = $this->getComponentType($componentName);

        if ($componentType === self::NO_COMPONENT) {
            throw new ParseException(sprintf('Invalid component name "%s"', $componentName));
        } elseif ($componentType === self::X_COMPONENT) {
            $component = $this->newXComponent($componentName);
        } else {
            $component = $this->newComponent($componentType);
        }

        if ($componentType === 'Calendar') {
            if (count($this->components) > 0) {
                throw new ParseException('Calendar component found outside of root');
            }
        } else {
            if (count($this->components) === 0) {
                throw new ParseException('Component found inside root which does not belong there');
            }
        }

        $this->_components->push($component);
    }

    /**
     * Handle the ending of a component
     *
     * @return void
     */
    protected function handleComponentEnd()
    {
        $componentName    = $this->getNextValue();
        $componentType    = $this->getComponentType($componentName);
        $currentComponent = $this->components->pop();

        if ($componentType === self::NO_COMPONENT) {
            throw new ParseException(sprintf('Invalid component name "%s"', $componentName));
        } elseif ($componentType === self::X_COMPONENT) {
            if ($componentType !== $currentComponent->getName()) {
                throw new ParseException(sprintf('Ending component does not match current component'));
            }
        } else {
            $className = 'Zend\Ical\Component\\' . $componentType;

            if (!$currentComponent instanceof $className) {
                throw new ParseException('Ending component does not match current component');
            }
        }

        if ($componentType === 'Calendar') {
            $this->_ical->addCalendar($currentComponent);
        } else {
            $this->_components->top()->addComponent($currentComponent);
        }
    }

    /**
     * Get a property name
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
     * Get the component type for a component
     *
     * @param  string $component
     * @return mixed
     */
    protected function getComponentType($component)
    {
        $component = strtoupper($component);

        if (!isset($this->componentTypes[$component])) {
            if (preg_match('(^' . $this->regex['x-name'] . '$)', $component)) {
                return self::X_COMPONENT;
            }

            return self::NO_COMPONENT;
        }

        return $this->componentTypes[$component];
    }

    /**
     * Get the property type for a property
     *
     * @param  string $property
     * @return mixed
     */
    protected function getPropertyType($property)
    {
        return $property;
    }

    /**
     * Get a new XName component
     *
     * @param  string $name
     * @return Component\XName
     */
    protected function newXComponent($name)
    {
        return new Component\XName($name);
    }

    /**
     * Get a new component
     *
     * @param  string $type
     * @return mixed
     */
    protected function newComponent($type)
    {
        $className = 'Zend\Ical\Component\\' . $type;
        return new $className();
    }

    /**
     * Get the next unfolded line from the stream
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
}
