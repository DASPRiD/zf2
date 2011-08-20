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
 * Last modified property.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Property
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class LastModified extends AbstractProperty
{
    /**
     * Last modified.
     * 
     * @var integer
     */
    protected $lastModified;
    
    /**
     * Create a new last modified property.
     * 
     * @param  mixed $lastModified
     * @return void
     */
    public function __construct($lastModified)
    {
        $this->setLastModified($lastModified);
    }
    
    /**
     * Set last modified.
     * 
     * @param  mixed $lastModified
     * @return self
     */
    public function setLastModified($lastModfied)
    {
        if ($lastModified instanceof \Zend\Date\Date) {
            $lastModified = $lastModified->getUnixTimestamp();
        } elseif ($lastModified instanceof \DateTime) {
            $lastModified = $lastModified->getTimestamp();
        } else {
            $lastModfied = (int) $lastModified;
        }
        
        $this->lastModified = $lastModified;
        return $this;
    }
    
    /**
     * Get last modified.
     * 
     * @return integer
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }
}
