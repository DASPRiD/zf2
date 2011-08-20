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

use Zend\Ical\Property;

/**
 * Calendar component.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Component
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Calendar extends AbstractComponent
{
    /**
     * Calendar version.
     * 
     * @var Property\Version
     */
    protected $version;
    
    /**
     * Product ID.
     * 
     * @var Property\ProdId
     */
    protected $productId;
    
    /**
     * Calendar scale.
     * 
     * @var Property\CalScale
     */
    protected $calendarScale;
    
    /**
     * Method.
     * 
     * @var Property\Method
     */
    protected $method;
    
    /**
     * Timezones.
     * 
     * @var array
     */
    protected $timezones = array();
    
    /**
     * Events.
     * 
     * @var array
     */
    protected $events = array();
    
    /**
     * IANA components.
     * 
     * @var array
     */
    protected $ianaComponents = array();
    
    /**
     * Vendor components.
     * 
     * @var array
     */
    protected $vendorComponents = array();
    
    /**
     * Set calendar version.
     * 
     * @param  mixed $version
     * @return self
     */
    public function setVersion($version)
    {
        if (!$version instanceof Property\Version) {
            $version = new Property\Version($version);
        }
        
        $this->version = $version;
        return self;
    }
    
    /**
     * Get version property.
     * 
     * @return Property\Version
     */
    public function getVersion()
    {
        if ($this->version === null) {
            $this->version = new Property\Version();
        }
        
        return $this->version;
    }
    
    /**
     * Set product ID.
     * 
     * @param  Property\ProductId $productId
     * @return self
     */
    public function setProductId($productId)
    {
        if (!$productId instanceof Property\ProductId) {
            $productId = new Property\ProductId($productId);
        }
        
        $this->productId = $productId;
        return $this;
    }
    
    /**
     * Get product ID.
     * 
     * @return Property\ProductId
     */
    public function getProductId()
    {
        if ($this->productId === null) {
            $this->productId = new Property\ProductId();
        }
        
        return $this->productId;
    }
    
    /**
     * Set calendar scale.
     * 
     * @param  mixed $calendarScale
     * @return self
     */
    public function setCalendarScale($calendarScale = null)
    {
        if ($calendarScale !== null && !$calendarScale instanceof Property\CalendarScale) {
            $calendarScale = new Property\CalendarScale($calendarScale);
        }
        
        $this->calendarScale = $calendarScale;
        return $this;
    }
    
    /**
     * Get calendar scale.
     * 
     * @return Property\CalendarScale
     */
    public function getCalendarScale()
    {
        return $this->calendarScale;
    }
    
    /**
     * Set method.
     * 
     * @param  mixed $method
     * @return self
     */
    public function setMethod($method = null)
    {
        if ($method !== null && !$method instanceof Property\Method) {
            $method = new Property\Method($method);
        }
        
        $this->method = $method;
        return $this;
    }
    
    /**
     * Get method.
     * 
     * @return Property\Method
     */
    public function getMethod()
    {
        return $this->method;
    }
    
    /**
     * Add an event.
     * 
     * @param  Event $event
     * @return string
     */
    public function addEvent(Event $event)
    {
        $uid = $event->getUid()->getUid();
        $this->events[$uid] = $event;
        return $uid;
    }
    
    /**
     * Add a timezone.
     * 
     * @param  string   $timezoneId
     * @param  Timezone $timezone 
     * @return self
     */
    public function addTimezone($timezoneId, Timezone $timezone)
    {
        $this->timezones[$timezoneId] = $timezone;
        return $this;
    }
    
    /**
     * Remove a timezone.
     * 
     * @param  string $timezoneId
     * @return self
     */
    public function removeTimezone($timezoneId)
    {
        if (!isset($this->timezones[$timezoneId])) {
            throw new OutOfBoundsException(sprintf('Timezone with ID "%s" does not exist', $timezoneId));
        }
        
        unset($this->timezones[$timezoneId]);
        return $this;
    }
       
    /**
     * Add an IANA component.
     * 
     * @param  Iana $component
     * @return self
     */
    public function addIanaComponent(Iana $component)
    {
        $this->ianaComponents[] = $component;
        return $this;        
    }
    
    /**
     * Add a vendor component.
     * 
     * @param  Vendor $component
     * @return self
     */
    public function addVendorComponent(Vendor $component)
    {
        $this->vendorComponents[] = $component;
        return $this;        
    }
    
    /**
     * Generate a globablly unique identifier.
     * 
     * @return string
     */
    protected function generateUid()
    {
        return gmdate('Ymd') . 'T' . gmdate('His') . 'Z-' . uniqid('', true) . '@' . gethostname();
    }
}
