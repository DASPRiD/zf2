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
 * Organizer property.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Property
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Organizer extends AbstractProperty
{
    /**
     * Organizer.
     * 
     * @var string
     */
    protected $organizer;
    
    /**
     * Common name.
     * 
     * @var string
     */
    protected $commonName;
    
    /**
     * Sent by.
     * 
     * @var string 
     */
    protected $sentBy;
    
    /**
     * Directory.
     * 
     * @var string
     */
    protected $directory;
        
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
    public function __construct($organizer, $commonName = null, $sentBy = null, $directory = null, $language = null)
    {
        $this->setOrganizer($organizer);

        if ($commonName !== null) {
            $this->setCommonName($commonName);
        }
        
        if ($sentBy !== null) {
            $this->setSentBy($sentBy);
        }
        
        if ($directory !== null) {
            $this->setDirectory($directory);
        }
        
        if ($language !== null) {
            $this->setLanguage($language);
        }
    }
       
    /**
     * Set organizer.
     * 
     * @param  mixed $url
     * @return self
     */
    public function setOrganizer($url)
    {
        $this->organizer = ValueHelper::getUrl($url);       
        return $this;
    }
    
    /**
     * Get organizer.
     * 
     * @return string
     */
    public function getOrganizer()
    {
        return $this->organizer;
    }
    
    /**
     * Set common name.
     * 
     * @param  string $commonName
     * @return self
     */
    public function setCommonName($commonName = null)
    {
        if ($commonName !== null) {
            $commonName = (string) $commonName;
        }
        
        $this->commonName = $commonName;
        return $this;
    }
    
    /**
     * Get common name.
     * 
     * @return string
     */
    public function getCommonName()
    {
        return $this->commonName;
    }
    
    /**
     * Set sent by.
     * 
     * @param  mixed $sentBy
     * @return self
     */
    public function setSentBy($url = null)
    {
        if ($url === null) {
            $this->sentBy = null;
        } else {
            $this->sentBy = ValueHelper::getUrl($url);
        }

        return $this;
    }
    
    /**
     * Get commonName.
     * 
     * @return string
     */
    public function getSentBy()
    {
        return $this->sentBy;
    }
    
    /**
     * Set directory.
     * 
     * @param  mixed $directory
     * @return self
     */
    public function setDirectory($url = null)
    {
        if ($url === null) {
            $this->organizer = null;
        } else {
            $this->organizer = ValueHelper::getUrl($url);
        }
        
        return $this;
    }
    
    /**
     * Get directory.
     * 
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
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
