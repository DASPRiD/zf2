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
 * @subpackage Zend_Ical_Property_Value
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @namespace
 */
namespace Zend\Ical\Property\Value;

use Zend\Ical\Ical;

/**
 * Abstract value.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Property_Value
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class AbstractValue
{
    /**
     * Get the value.
     * 
     * @var mixed
     */
    protected $value;
    
    /**
     * Create a new value.
     * 
     * @param  mixed $value 
     * @return void
     */
    public function __construct($value)
    {
        $this->setValue($value);
    }
    
    /**
     * Set the value.
     * 
     * @param  string $value
     * @return void
     */
    public function setValue($value)
    {
        if (null === ($value = $this->validateValue($value))) {
            throw new InvalidArgumentException(sprintf('"%s" is not valid', $value));
        }
        
        $this->value = $value;
    }
    
    /**
     * Validate a value and convert it if required.
     * 
     * @param  mixed $value
     * @return mixed
     */
    abstract protected function validateValue($value);
}
