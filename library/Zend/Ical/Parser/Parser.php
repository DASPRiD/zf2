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
    Zend\Ical\Component;

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
     * Special component types
     */
    const NO_COMPONENT = 0;
    const X_COMPONENT  = 1;

    /**
     * Stream resource
     *
     * @var resource
     */
    protected $_stream;

    /**
     * Buffer for getting folded lines
     *
     * @var string
     */
    protected $_buffer;

    /**
     * Raw data of the current line
     *
     * @var string
     */
    protected $_rawData;

    /**
     * Current position in the current line
     *
     * @var integer
     */
    protected $_currentPos;

    /**
     * Ical object
     * 
     * @var Ical
     */
    protected $_ical;

    /**
     * Component stack
     *
     * @var array
     */
    protected $_components;

    /**
     * Component type map
     *
     * @var array
     */
    protected $_componentTypes = array(
        'VCALENDAR' => 'Component\Calendar',
        'VALARM'    => 'Component\Alarm',
        'VEVENT'    => 'Component\Event',
        'VFREEBUSY' => 'Component\FreeBusy',
        'VJOURNAL'  => 'Component\Journal',
        'VTODO'     => 'Component\Todo',
        'VTIMEZONE' => 'Component\Timezone'
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

        $this->_stream = $stream;

        // Base regex types
        $this->_regex = array(
            'iana-token'   => '[A-Za-z\d\-]+',
            'x-name'       => 'X-[A-Za-z\d]{3,}-[A-Za-z\d\-]+',
            'safe-char'    => '[\x20\x09\x21\x23-\x2B\x2D-\x39\x3C-\x7E\x80-\xFB]',
            'qsafe-char'   => '[\x20\x09\x21\x23-\x7E\x80-\xFB]',
            'tsafe-char'   => '[\x20\x21\x23-\x2B\x2D-\x39\x3C-\x5B\x5D-\x7E\x80-\xFB]',
            'value-char'   => '[\x20\x09\x21-\x7E\x80-\xFB]',
            'escaped-char' => '(?:\\\\|\\;|\\,|\\\\N|\\\\n)'
        );

        // Regex types based on base type
        $this->_regex['param-text']    = '(' . $this->_regex['safe-char'] . '*)';
        $this->_regex['quoted-string'] = '"(' . $this->_regex['qsafe-char'] . '*)"';
        $this->_regex['name']          = '(?:'. $this->_regex['x-name'] . '|' . $this->_regex['iana-token'] . ')';
        $this->_regex['param-name']    = '(?:('. $this->_regex['x-name'] . ')|(' . $this->_regex['iana-token'] . '))';
        $this->_regex['param-value']   = '(?:'. $this->_regex['quoted-string'] . '|' . $this->_regex['param-text'] . ')';
        $this->_regex['text']          = '((?:' . $this->_regex['tsafe-char'] . '|' . $this->_regex['escaped-char'] . '|[:"])*)';
        $this->_regex['value']         = '(?:' . $this->_regex['value-char'] . '*)';
    }

    /**
     * Parse the input from the stream
     *
     * @return Ical
     */
    public function parse()
    {
        $this->_ical       = new Ical();
        $this->_components = new SplStack();

        while (($this->_rawData = $this->_getNextUnfoldedLine() !== null)) {
            $this->_parseLine();
        }

        if (count($this->_components) > 0) {
            throw new ParseException('Unexpected end in input stream');
        }

        return $this->_ical;
    }

    /**
     * Parse a single line
     *
     * @return void
     */
    protected function _parseLine()
    {
        $this->_currentPos = 0;
        $propertyName      = $this->_getPropertyName();

        if ($propertyName === null) {
            throw new ParseException('Could not find a property name, component begin or end tag');
        }

        // If the property name is BEGIN or END, we are actually starting or
        // ending a new component.
        if (strcasecmp($match['name'], 'BEGIN') === 0) {
            return $this->_handleComponentBegin();
        } elseif (strcasecmp($match['name'], 'END') === 0) {
            return $this->_handleComponentEnd();
        }

        // At this point, the property name really is a property name (Not a
        // component name), so make a new property and add it to the component.
        if (count($this->_components) === 0) {
            throw new ParseException('Found property name within root');
        }

        $propertyType = $this->_getPropertyType($propertyName);

        if ($propertyType === self::NO_PROPERTY) {
            throw new ParseException('Invalid property name');
        }

        $property = new Property($propertyType);

        $this->_components->top()->addProperty($property);
    }

    /**
     * Handle the beginning of a component
     *
     * @return void
     */
    protected function _handleComponentBegin()
    {
        $componentName = $this->_getNextValue();
        $componentType = $this->_getComponentType();

        if ($componentType === self::NO_COMPONENT) {
            throw new ParseException('Invalid component name');
        } elseif ($componentType === self::X_COMPONENT) {
            $component = $this->_newXComponent($componentName);
        } else {
            $component = $this->_newComponent($componentType);
        }

        $this->_components->push($component);

        return true;
    }

    /**
     * Handle the ending of a component
     *
     * @return void
     */
    protected function _handleComponentEnd()
    {
        $componentName    = $this->_getNextValue();
        $componentType    = $this->_getComponentType();
        $currentComponent = $this->_components->pop();

        if ($componentType === self::NO_COMPONENT) {
            throw new ParseException('Invalid component name');
        } elseif ($componentType === self::X_COMPONENT) {
            if ($componentType !== $currentComponent->getName()) {
                throw new ParseException('Ending component does not match current component');
            }
        } else {
            if (!$currentComponent instanceof $componentType) {
                throw new ParseException('Ending component does not match current component');
            }
        }

        if ($componentType === 'Component/Calendar') {
            if (count($this->_components) > 0) {
                throw new ParseException('Calendar component found outside of root');
            }

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
    protected function _getPropertyName()
    {
        if (!preg_match(
            '(\G(?<name>' . $this->_regex['name'] . ')[;:])S',
            $this->_rawData, $match, $this->_currentPos
        )) {
            return null;
        }

        $this->_currentPos += strlen($match[0]);

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
    protected function _getNextValue($kind)
    {
        if (!preg_match(
            '(\G(?<value>' . $this->_regex['value'] . ')(?<sep>,|\r\n))S',
            $this->_rawData, $match, $this->_currentPos
        )) {
            return null;
        }

        $this->_currentPos += strlen($match[0]);

        return $match['value'];
    }

    /**
     * Get the component type for a component
     *
     * @param  string $component
     * @return mixed
     */
    protected function _getComponentType($component)
    {
        $component = strtoupper($component);

        if (!in_array($component, $this->_componentTypes)) {
            if (preg_match('(^' . $this->_regex['x-name'] . '$)', $component)) {
                return self::X_COMPONENT;
            }

            return self::NO_COMPONENT;
        }

        return $this->_componentTypes[$componentName];
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
        return new $type();
    }

    /**
     * Get the next unfolded line from the stream
     *
     * @return string
     */
    protected function _getNextUnfoldedLine()
    {
        if (feof($this->_stream)) {
            return null;
        }

        $rawData = $this->_buffer . fgets($this->_stream);

        while (
            !feof($this->_stream) && $this->_buffer = fgetc($this->_stream)
            && ($buffer === ' ' || $buffer === "\t")
        ) {
            $rawData       = rtrim($rawData, "\r\n") . fgets($this->_stream);
            $this->_buffer = '';
        }

        return $rawData;
    }
}
