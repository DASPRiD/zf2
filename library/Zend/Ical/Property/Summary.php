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
 * Summary property.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Property
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Summary extends AbstractProperty
{
    /**
     * Summary.
     * 
     * @var string
     */
    protected $summary;
    
    /**
     * Alternative representation.
     * 
     * @var string
     */
    protected $alternativeRepresentation;
    
    /**
     * Language
     * 
     * @var string
     */
    protected $language;
    
    /**
     * Create a new summary property.
     * 
     * @param  string $summary
     * @param  mixed  $alternativeRepresentation
     * @param  string $language
     * @return void
     */
    public function __construct($summary, $alternativeRepresentation = null, $language = null)
    {
        $this->setSummary($summary);
        
        if ($alternativeRepresentation !== null) {
            $this->setAlternativeRepresentation($alternativeRepresentation);
        }
        
        if ($language !== null) {
            $this->setLanguage($language);
        }
    }
    
    /**
     * Set summary.
     * 
     * @param  string $summary
     * @return self
     */
    public function setSummary($summary)
    {
        $this->summary = (string) $summary;
        return $this;
    }
    
    /**
     * Get summary.
     * 
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }
    
    /**
     * Set alternative representation.
     * 
     * @param  string $url
     * @return self
     */
    public function setAlternativeRepresentation($url = null)
    {
        if ($url === null) {
            $this->alternativeRepresentation = null;
        } else {
            $this->alternativeRepresentation = ValueHelper::getUrl($url);
        }
        
        return $this;
    }
    
    /**
     * Get alternative representation.
     * 
     * @return string
     */
    public function getAlternativeRepresentation()
    {
        return $this->alternativeRepresentation;
    }
    
    /**
     * Set language.
     * 
     * @param  string $language
     * @return self
     */
    public function setLanguage($language = null)
    {
        if ($language === null) {
            $this->language = null;
        } else {
            $this->language = ValueHelper::getLanguage($language);
        }
        
        return $this;
    }
    
    /**
     * Get language.
     * 
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }
}
