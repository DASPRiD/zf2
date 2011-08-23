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
 * UID value.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Property
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Uid implements Value
{
    /**
     * UID.
     * 
     * @var string
     */
    protected $uid;
    
    /**
     * Create a new UID value.
     * 
     * @param  string $uid
     * @return void
     */
    public function __construct($uid = null)
    {
        if ($uid === null) {
            $this->generateNewUid();
        } else {
            $this->setUid($uid);
        }
    }
    
    /**
     * Set UID.
     * 
     * @param  string $uid
     * @return self
     */
    public function setUid($uid)
    {                
        $this->uid = (string) $uid;
        return $this;
    }
    
    /**
     * Generate a new UID.
     * 
     * @return self
     */
    public function generateNewUid()
    {
        $this->uid = gmdate('Ymd') . 'T' . gmdate('His') . 'Z-' . uniqid('', true) . '@' . gethostname();
        return $this;
    }
    
    /**
     * Get UID.
     * 
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }
}
