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

use Zend\Ical,
    Zend\Ical\Property;

/**
 * Event component.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Component
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Event extends AbstractComponent
{
    /**
     * Classification.
     * 
     * @var Property\Classification
     */
    protected $classification;
    
    protected $created;
    
    protected $description;
    
    protected $dtStart;
    
    protected $geo;
    
    protected $lastModified;
    
    protected $location;
    
    /**
     * Organizer.
     * 
     * @var Property\Organizer
     */
    protected $organizer;
    
    protected $priority;
    
    /**
     * Date/Time stamp.
     * 
     * @var Property\DateTimeStamp
     */
    protected $dateTimeStamp;
    
    protected $sequence;
    
    protected $status;
    
    /**
     * Summary.
     * 
     * @var Property\Summary
     */
    protected $summary;
    
    protected $transp;
    
    /**
     * UID
     * 
     * @var Property\Uid
     */
    protected $uid;
    
    protected $url;
    
    protected $recurId;
    
    protected $dtEnd;
    
    protected $duration;
    
    /**
     * Create a new event component.
     * 
     * @param  string $uid
     * @return void
     */
    public function __construct($uid = null)
    {
        if ($uid !== null) {
            $this->setUid($uid);
        }
    }
    
    /**
     * Set UID.
     * 
     * @param  mixed $uid
     * @return self
     */
    public function setUid($uid)
    {
        if (!$uid instanceof Property\Uid) {
            $uid = new Property\Uid($uid);
        }
        
        $this->uid = $uid;
        return $this;
    }
    
    /**
     * Get organizer.
     * 
     * @return Property\Uid
     */
    public function getUid()
    {
        if ($this->uid === null) {
            $this->uid = new Property\Uid();
        }
        
        return $this->uid;
    }
    
    /**
     * Set classification.
     * 
     * @param  mixed $classification 
     * @return self
     */
    public function setClassification($classification)
    {
        if (!$classification instanceof Property\Classification) {
            $classification = new Property\Classification($classification);
        }
        
        $this->classification = $classification;
        return $this;
    }
    
    /**
     * Get classification.
     * 
     * @return Property\Classification
     */
    public function getClassification()
    {
        return $this->classification;
    }
    
    /**
     * Set organizer.
     * 
     * @param  mixed $organizer
     * @return self
     */
    public function setOrganizer($organizer)
    {
        if (!$organizer instanceof Property\Summary) {
            $organizer = new Property\Organizer($organizer);
        }
        
        $this->organizer = $organizer;
        return $this;
    }
    
    /**
     * Get organizer.
     * 
     * @return Property\Organizer
     */
    public function getOrganizer()
    {
        return $this->organizer;
    }
    
    /**
     * Set Date/Time stamp.
     * 
     * @param  mixed $dateTimeStamp
     * @return self
     */
    public function setDateTimeStamp($dateTimeStamp)
    {
        if (!$dateTimeStamp instanceof Property\Summary) {
            $dateTimeStamp = new Property\Organizer($dateTimeStamp);
        }
        
        $this->dateTimeStamp = $dateTimeStamp;
        return $this;
    }
    
    /**
     * Get Date/Time stamp.
     * 
     * @return Property\Organizer
     */
    public function getDateTimeStamp()
    {
        return $this->dateTimeStamp;
    }
    
    /**
     * Set classification.
     * 
     * @param  mixed $summary
     * @return self
     */
    public function setSummary($summary)
    {
        if (!$summary instanceof Property\Summary) {
            $summary = new Property\Summary($summary);
        }
        
        $this->summary = $summary;
        return $this;
    }
    
    /**
     * Get summary.
     * 
     * @return Property\Summary
     */
    public function getSummary()
    {
        return $this->summary;
    }
}
