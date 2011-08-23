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

use Zend\Ical\Property\PropertyList;

/**
 * Abstract custom container component.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Component
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class AbstractCustomContainerComponent extends AbstractComponent
{
    /**
     * Experimental components.
     * 
     * @var array
     */
    protected $experimentalComponents = array();
    
    /**
     * IANA components
     * 
     * @var array
     */
    protected $ianaComponents = array();
    
    /**
     * Add an experimental component.
     * 
     * @param  Experimental $component
     * @return self 
     */
    public function addExperimentalComponent(Experimental $component)
    {
        $this->experimentalComponents[] = $component;
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
}
