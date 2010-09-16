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
 * @subpackage Zend_Ical_Analyzer
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @namespace
 */
namespace Zend\Ical\Analyzer;

/**
 * Ical lexer implemented as DFA (Deterministic Finite-state Automaton)
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Analyzer
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Lexer
{
    const T_NAME = 0;

    /**
     * File stream
     *
     * @var resource
     */
    protected $_stream;

    /**
     * List of possible tokens
     *
     * @var aray
     */
    protected $_tokens;

    /**
     * Create a new lexer with an open file stream.
     *
     * @param  resource $stream
     * @return void
     */
    public function __construct($stream)
    {
        if (!is_resource($stream)) {
            throw new InvalidArgumentException('Stream must be a resource');
        }

        $this->_stream = $stream;

        // Base regex types
        $this->_regex = array(
            'iana-token'   => '[A-Za-z\d\-]+',
            'x-name'       => 'X-[A-Za-z\d]{3,}-[A-Za-z\d\-]+',
            'safe-char'    => '[\x20\x09\x21\x23-\x2B\x2D-\x39\x3C-\x7E\x80-\xFB]',
            'qsafe-char'   => '[\x20\x09\x21\x23-\x7E\x80-\xFB]',
            'tsafe-char'   => '[\x20\x21\x23-\x2B\x2D-\x39\x3C-\x5B\x5D-\x7E\x80-\xFB]',
            'value-char'   => '[\x20\x09\x21-\x7E\x80-\xFB]',
            'escaped-char' => '(?:\\\\|\\;|\\,|\\\\N|\\\\n)'
        );

        // Regex types based on base type
        $this->_regex['param-text']    = '(' . $this->_regex['safe-char'] . '*)';
        $this->_regex['quoted-string'] = '"(' . $this->_regex['qsafe-char'] . '*)"';
        $this->_regex['name']          = '(?:('. $this->_regex['x-name'] . ')|(' . $this->_regex['iana-token'] . '))';
        $this->_regex['param-name']    = '(?:('. $this->_regex['x-name'] . ')|(' . $this->_regex['iana-token'] . '))';
        $this->_regex['param-value']   = '(?:'. $this->_regex['quoted-string'] . '|' . $this->_regex['param-text'] . ')';
        $this->_regex['text']          = '((?:' . $this->_regex['tsafe-char'] . '|' . $this->_regex['escaped-char'] . '|[:"])*)';
        $this->_regex['value']         = '(' . $this->_regex['value-char'] . '*)';
    }

    /**
     * Tokenize the input stream.
     *
     * The returned array will contain all tokens, where is token is represented
     * by an array with two keys, 0 (type) and 1 (lexeme). For performance and
     * memory reasons we don't use a objects for the tokens.
     *
     * @return array
     */
    public function tokenize()
    {
        $tokens = array();
        $buffer;

        while (!feof($this->_stream)) {
            $rawData = $buffer . fgets($this->_stream);
            
            // Unfold the line
            while (!feof($this->_stream) && $this->_buffer = fgetc($this->_stream) && ($buffer === ' ' || $buffer === "\t")) {
                $rawData = rtrim($rawData, "\r\n") . fgets($this->_stream);
            }

            $rawDataLength = strlen($rawData);
            $currentPos    = 0;
            
            while ($rawDataLength > $currentPos) {
                error:
                throw new InvalidInputException('Unexpected input found');
            }
        }


        return $tokens;
    }
}
