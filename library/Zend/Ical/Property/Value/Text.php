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
namespace Zend\Ical\Property\Value;

/**
 * Text value.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Property
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Text implements Value
{
    /**
     * Text.
     * 
     * @var string
     */
    protected $text;
    
    /**
     * Create a new text property.
     * 
     * @param  string $text
     * @return void
     */
    public function __construct($text)
    {
        $this->setText($text);
    }
    
    /**
     * Set text.
     * 
     * @param  string $text
     * @return self
     */
    public function setText($text)
    {                
        $this->text = (string) $text;
        return $this;
    }
    
    /**
     * Get text.
     * 
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
    
    /**
     * fromString(): defined by Value interface.
     * 
     * @see    Value::fromString()
     * @param  string $string
     * @return Value
     */
    public static function fromString($string)
    {
        return new self($string);
    }
}
