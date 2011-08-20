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

use Zend\Ical,
    Zend\Ical\Exception;

/**
 * Method property.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Property
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Url extends AbstractProperty
{
    /**
     * URL.
     * 
     * @var string
     */
    protected $url;
    
    /**
     * Create a new URL property.
     * 
     * @param  mixed $url
     * @return void
     */
    public function __construct($url)
    {
        $this->setUrl($url);
    }
    
    /**
     * Set URL.
     * 
     * @param  mixed $url
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = ValueHelper::getUrl($url);
        return $this;
    }
    
    /**
     * Get URL.
     * 
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
