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
namespace Zend\Ical\Component;

use Zend\Ical\Exception;

/**
 * Timezone component.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Component
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Timezone extends AbstractComponent
{
    /**
     * Timezone ID.
     * 
     * @var Property\TimezoneId
     */
    protected $timezoneId;
    
    /**
     * Offsets.
     * 
     * @var array
     */
    protected $offsets;
    
    /**
     * Createa a new timezone component.
     * 
     * @param  mixed $offsets
     * @param  mixed $url
     * @param  mixed $lastModified
     * @return void
     */
    public function __construct($offsets, $url = null, $lastModified = null)
    {
        $this->setOffsets($offsets);
        
        if ($url !== null) {
            $this->setUrl($url);
        }
        
        if ($lastModified !== null) {
            $this->setLastModified($lastModified);
        }
    }
    
    /**
     * Add an offset to the timezone.
     * 
     * @param  AbstractOffset $offset
     * @return self
     */
    public function addOffset(AbstractOffset $offset)
    {
        $this->offsets[] = $offset;
        return $this;
    }
    
    /**
     * Set offsets.
     * 
     * $offsets must either be an instance of 'Standard' or 'Daylight', or an
     * array consiting of at least one 'Standard' and 'Daylight' component.
     * 
     * @param  mixed $offsets
     * @return self
     */
    public function setOffsets($offsets)
    {
        if ($offsets instanceof AbstractOffset) {
            $offsets = array($offsets);
        } elseif (!is_array($offsets)) {
            throw new Exception\InvalidArgumentException('Offset is no instance of AbstractOffset, nor an array');
        }
        
        $this->offsets = array();
        
        foreach ($offsets as $offset) {
            if (!$offsets instanceof AbstractOffset) {
                throw new Exception\UnexpectedValueException('Offset is no instance of Standard or Daylight');
            }
            
            $this->offsets[] = $offset;
        }

        return $this;
    }
    
    /**
     * Get offsets.
     * 
     * @return array
     */
    public function getOffsets()
    {
        return $this->offsets;
    }
    
    /**
     * Set URL.
     * 
     * @param  mixed $url
     * @return self
     */
    public function setUrl($url)
    {
        if (!$url instanceof Property\Url) {
            $url = new Property\Url($url);
        }
        
        $this->url = $url;
        return $this;
    }
    
    /**
     * Get URL.
     * 
     * @return Property\Url
     */
    public function getUrl()
    {
        return $this->url;
    }
    
    /**
     * Set last modified.
     * 
     * @param  mixed $lastModified
     * @return self
     */
    public function setLastModified($lastModified)
    {
        if (!$lastModified instanceof Property\LastModified) {
            $lastModified = new Property\LastModified($lastModified);
        }
        
        $this->lastModified = $lastModified;
        return $this;
    }
    
    /**
     * Get last modified.
     * 
     * @return Property\LastModified 
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }
}
