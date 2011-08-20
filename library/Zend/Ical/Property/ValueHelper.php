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
 * Value helper.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Property
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ValueHelper
{
    /**
     * Get language.
     * 
     * @param  mixed $language
     * @return string
     */
    public static function getLanguage($language)
    {
        if (!preg_match('(^[A-Za-z]+(?:-[A-Za-z]+)*$)S', $language)) {
            throw new Exception\InvalidArgumentException(sprintf('"%s" is not a valid language according to RFC 1766', $language));
        }
        
        return $language;
    }
    
    /**
     * Get URL.
     * 
     * @param  mixed $url
     * @return self
     */
    public static function getUrl($url)
    {
        if (!$url instanceof \Zend\Uri\Uri) {
            $uri = new \Zend\Uri\Uri($url);
        }
               
        if (!$uri->isValid() || !$uri->isAbsolute()) {
            throw new Exception\InvalidArgumentException('Supplied URI is not valid or not absolute');
        }
        
        return $uri->toString();
    }
}
