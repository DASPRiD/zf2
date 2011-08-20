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
namespace Zend\Ical\Property;

use Zend\Ical\Ical;

/**
 * Property parameter.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Component
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Parameter
{
    /**
     * Parameter map.
     *
     * @var array
     */
    protected static $parameterMap = array(
        'ALTREPT'        => array('type' => 'URI'),
        'CN'             => array('type' => 'TEXT'),
        'CUTYPE'         => array('type' => 'ENUM', 'values' => array(
            'INDIVIDUAL',
            'GROUP',
            'RESOURCE',
            'ROOM',
            'UNKNOWN',
            'iana-token',
            'x-name'
        )),
        'DELEGATED-FROM' => array('type' => 'CAL-ADDRESS', 'multiple' => true),
        'DELEGATED-TO'   => array('type' => 'CAL-ADDRESS', 'multiple' => true),
        'DIR'            => array('type' => 'URI'),
        'ENCODING'       => array('type' => 'ENUM', 'values' => array(
            '8BIT',
            'BASE64',
            'iana-token',
            'x-name'
        )),
        'FMTYPE'         => array('type' => 'ENUM', 'values' => array(
            'iana-token',
            'x-name'
        )),
        'FBTYPE'         => array('type' => 'ENUM', 'values' => array(
            'FREE',
            'BUSY',
            'BUSY-UNAVAILABLE',
            'BUSY-TENTATIVE',
            'iana-token',
            'x-name'
        )),
        'LANGUAGE'       => array('type' => 'TEXT'),
        'MEMBER'         => array('type' => 'CAL-ADDRESS', 'multiple' => true),
        'PARTSTAT'       => array('type' => 'ENUM', 'values' => array(
            'NEEDS-ACTION',
            'ACCEPTED',
            'DECLINED',
            'TENTATIVE',
            'DELEGATED',
            'COMPLETED',
            'IN-PROGRESS',
            'iana-token',
            'x-name'
        )),
        'RANGE'          => array('type' => 'ENUM', 'values' => array('THISANDPRIOR', 'THISANDFUTURE')),
        'RELATED'        => array('type' => 'ENUM', 'values' => array('START', 'END')),
        'RELTYPE'        => array('type' => 'ENUM', 'values' => array(
            'PARENT',
            'CHILD',
            'SIBLING',
            'iana-token',
            'x-name'
        )),
        'ROLE'           => array('type' => 'ENUM', 'values' => array(
            'CHAIR',
            'REQ-PARTICIPANT',
            'OPT-PARTICIPANT',
            'NON-PARTICIPANT',
            'iana-token',
            'x-name'
        )),
        'RSVP'           => array('type' => 'BOOLEAN'),
        'SENT-BY'        => array('type' => 'CAL-ADDRESS', 'multiple' => false),
        'TZID'           => array('type' => 'TEXT'),
        'VALUE'          => array('type' => 'ENUM', 'values' => array(
            'BINARY',
            'BOOLEAN',
            'CAL-ADDRESS',
            'DATE',
            'DATE-TIME',
            'DURATION',
            'FLOAT',
            'INTEGER',
            'PERIOD',
            'RECUR',
            'TEXT',
            'TIME',
            'URI',
            'UTC-OFFSET',
            'iana-token',
            'x-name'
        ))
    );

    /**
     * Type of the value.
     * 
     * @var string
     */
    protected $type;
    
    /**
     * Value of the parameter.
     *
     * @var mixed
     */
    protected $value;

    /**
     * Create a new parameter with a given name.
     *
     * @param  string $name
     * @param  mixed  $value
     * @return void
     * @throws UnexpectedValueException If the given parameter name is not valid
     */
    public function __construct($name, $value)
    {
        if (isset(self::$parameterMap[$name])) {
            $this->type = self::$parameterMap[$name]['type'];
        } elseif (Ical::isXName($name)) {
            $this->type = 'TEXT';
        } elseif (Ical::isIanaToken($name)) {
            $this->type = 'TEXT';
        } else {
            throw new UnexpectedValueException(sprintf('The parameter name "%s" is not valid', $name));
        }

        $this->name = $name;

        $this->setValue($value);
    }

    /**
     * Set the value of the parameter.
     *
     * @param  mixed $value
     * @return void
     */
    public function setValue($value)
    {
        switch ($this->type) {
            case 'TEXT':
                $this->value = (string) $value;
                break;

            case 'BOOLEAN':
                if (is_bool($value)) {
                    $this->value = $value;
                } else {
                    if (strcmp($value, 'TRUE')) {
                        $this->value = true;
                    } elseif (strcmp($value, 'FALSE')) {
                        $this->value = false;
                    } else {
                        throw new UnexpectedValueException(sprintf('Boolean value "%s" matches neither "TRUE" nor "FALSE"', $value));
                    }
                }
                break;

            case 'ENUM':
                $allowedValues = self::$parameterMap[$this->name]['values'];

                if (in_array($value, $allowedValues) && $value !== 'iana-token' && $value !== 'x-name') {
                    $this->value = (string) $value;
                } elseif (in_array('x-name', $allowedValues) && Ical::isXName($value)) {
                    $this->value = (string) $value;
                } elseif (in_array('iana-token', $allowedValues) && Ical::isIanaToken($value)) {
                    $this->value = (string) $value;
                } else {
                    throw new UnexpectedValueException(sprintf('Enum value "%s" is not within allowed set', $value));
                }
                break;

            case 'URI':
                // @todo Use new Zend\Uri implementation when available
                $this->value = (string) $value;
                break;

            case 'CAL-ADDRESS':
                // @todo Validate address
                // @todo Handle multiple addresses
                $this->value = (string) $value;
                break;
        }
    }
}
