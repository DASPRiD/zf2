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

use Zend\Ical\Ical;

/**
 * Abstract property.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Property
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class AbstractProperty
{
    /**
     * Vendor parameters.
     * 
     * @var array
     */
    protected $vendorParameters = array();
    
    /**
     * Add a vendor parameter.
     * 
     * @param  string $name
     * @param  string $value 
     * @return self
     */
    public function addVendorParameter($name, $value)
    {
        $this->vendorParameters[$name] = $value;
        return $this;
    }
    
    /**
     * Sanitize a value by checking it and returning value type and value.
     * 
     * @param  mixed $value
     * @return array
     */
    protected function getSanitizedValue($value)
    {
        $isValid = false;
        
        foreach ($this->options['value-types'] as $valueType) {
            switch ($valueType) {
                case 'TEXT':
                    $value   = (string) $value;
                    $isValid = true;
                    break;
                
                case 'INTEGER':
                    if (ctype_digit($value)) {
                        $value   = (int) $value;
                        $isValid = true;
                    }
                    break;
                    
                case 'VERSION':
                    // Zend\Ical only supports version "2.0".
                    if ($value === '2.0') {
                        $isValid = true;
                    }
                    break;
                    
                case 'CALSCALE':
                    // "GREGORIAN" matches IANA-token format.
                    if (Ical::isIanaToken($value) || Ical::isXName($value)) {
                        $isValid = true;
                    }
                    break;
                    
                case 'CLASS':
                    // "PUBLIC", "PRIVATE" and "CONFIDENTAL" match IANA-token format.
                    if (Ical::isIanaToken($value) || Ical::isXName($value)) {
                        $isValid = true;
                    }
                    break;
                    
                case 'GEO':
                    if (preg_match('(^[+-]?\d+\.\d+,[+-]?\d+\.\d+$)S', $value)) {
                        $isValid = true;
                    }
                    break;
                    
                case 'UTC-OFFSET':
                    if (preg_match('(^[+-]\d{2}\d{2}\d{2}?$)S', $value)) {
                        $isValid = true;
                    }
                    break;
                    
                case 'DATE-TIME':
                    if (preg_match('(^(?<year>\d{4})(?<month>\d{2})(?<day>\d{2})T(?<hour>\d{2})(?<minute>\d{2})(?<second>\d{2})Z?$)S', $value)) {
                        $isValid = true;
                    }                    
                    break;
            }
        }
        
        if (!$isValid) {
            return null;
        }
        
        return array($valueType, $value);
    }
}
