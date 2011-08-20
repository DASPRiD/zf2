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
 * Version property.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Property
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Version extends AbstractProperty
{
    /**
     * Highest version.
     * 
     * @var string
     */
    protected $maxVersion;
    
    /**
     * Lowest version.
     * 
     * @var string
     */
    protected $minVersion;
    
    /**
     * Create a new version property.
     * 
     * @param  string $maxVersion
     * @param  string $minVersion
     * @return void
     */
    public function __construct($maxVersion = '2.0', $minVersion = null)
    {
        $this->setMaxVersion($maxVersion);
        
        if ($minVersion !== null) {
            $this->setMinVersion($minVersion);
        }
    }
    
    /**
     * Set highest version.
     * 
     * @param  string $version
     * @return self
     */
    public function setMaxVersion($version)
    {
        $this->maxVersion = (string) $version;
        return $this;
    }
    
    /**
     * Get highest version.
     * 
     * @return string
     */
    public function getMaxVersion()
    {
        return $this->maxVersion;
    }
    
    /**
     * Set lowest version.
     * 
     * @param  string $version
     * @return self
     */
    public function setMinVersion($version)
    {
        $this->minVersion = (string) $version;
        return $this;
    }
    
    /**
     * Get lowest version.
     * 
     * @return string
     */
    public function getMinVersion()
    {
        return $this->minVersion;
    }
}
