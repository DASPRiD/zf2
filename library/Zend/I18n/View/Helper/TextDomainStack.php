<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_I18n
 */

namespace Zend\I18n\View\Helper;

use SplStack;
use Zend\I18n\Exception;
use Zend\View\Helper\AbstractHelper;
use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\TranslatorAwareInterface;

/**
 * View helper for keeping trace of used text domains.
 *
 * @category   Zend
 * @package    Zend_I18n
 * @subpackage View
 */
class TextDomainStack extends AbstractHelper implements TranslatorAwareInterface
{
    /**
     * Stack of text domains.
     *
     * @var SplStack
     */
    protected $stack;

    /**
     * Translator instance.
     *
     * @var Translator
     */
    protected $translator;

    /**
     * Whether translator should be used.
     *
     * @var boolan
     */
    protected $translatorEnabled = true;

    /**
     * Create a new text domain stack.
     */
    public function __construct()
    {
        $this->stack = new SplStack();
    }

    /**
     * setTranslator(): defined by TranslatorAwareInterface.
     *
     * @see    TranslatorAwareInterface::setTranslator()
     * @param  Translator $translator
     * @param  string     $textDomain
     * @return TextDomainStack
     */
    public function setTranslator(Translator $translator = null, $textDomain = null)
    {
        $this->translator = $translator;

        if (null !== $textDomain) {
            $translator->setDefaultTextDomain($textDomain);
        }

        return $this;
    }

    /**
     * getTranslator(): defined by TranslatorAwareInterface.
     *
     * @see    TranslatorAwareInterface::getTranslator()
     * @return Translator|null
     */
    public function getTranslator()
    {
        if (!$this->isTranslatorEnabled()) {
            return null;
        }

        return $this->translator;
    }

    /**
     * hasTranslator(): defined by TranslatorAwareInterface.
     *
     * @see    TranslatorAwareInterface::hasTranslator()
     * @return boolean
     */
    public function hasTranslator()
    {
        return (bool) $this->getTranslator();
    }

    /**
     * setTranslatorEnabled(): defined by TranslatorAwareInterface.
     *
     * @see    TranslatorAwareInterface::setTranslatorEnabled()
     * @param  boolean $enabled
     * @return TextDomainStack
     */
    public function setTranslatorEnabled($enabled = true)
    {
        $this->translatorEnabled = (bool) $enabled;
        return $this;
    }

    /**
     * isTranslatorEnabled(): defined by TranslatorAwareInterface.
     *
     * @see    TranslatorAwareInterface::isTranslatorEnabled()
     * @return bool
     */
    public function isTranslatorEnabled()
    {
        return $this->translatorEnabled;
    }

    /**
     * setTranslatorTextDomain(): defined by TranslatorAwareInterface.
     *
     * @see    TranslatorAwareInterface::setTranslatorTextDomain()
     * @param  string $textDomain
     * @return TextDomainStack
     */
    public function setTranslatorTextDomain($textDomain = 'default')
    {
        $translator = $this->getTranslator();

        if (null === $translator) {
            throw new Exception\RuntimeException('Translator has not been set');
        }

        $translator->setDefaultTextDomain($textDomain);
        return $this;
    }

    /**
     * getTranslatorTextDomain(): defined by TranslatorAwareInterface.
     *
     * @see    TranslatorAwareInterface::getTranslatorTextDomain()
     *
     * @return string
     */
    public function getTranslatorTextDomain()
    {
        $translator = $this->getTranslator();

        if (null === $translator) {
            throw new Exception\RuntimeException('Translator has not been set');
        }

        return $translator->getDefaultTextDomain();
    }

    /**
     * Push a text domain on the stack.
     *
     * @param  string|null $textDomain
     * @return TextDomainStack
     * @throws Exception\RuntimeException
     */
    public function __invoke($textDomain = null)
    {
        $translator = $this->getTranslator();

        if (null === $translator) {
            throw new Exception\RuntimeException('Translator has not been set');
        } elseif (null !== $textDomain) {
            $this->pushTextDomain($textDomain);
        }

        return $this;
    }

    /**
     * Push a text domain on the stack.
     *
     * @param  string $textDomain
     * @throws Exception\RuntimeException
     */
    public function pushTextDomain($textDomain)
    {
        $translator = $this->getTranslator();

        if (null === $translator) {
            throw new Exception\RuntimeException('Translator has not been set');
        }

        $this->stack->push($translator->getDefaultTextDomain());
        $translator->setDefaultTextDomain($textDomain);
    }

    /**
     * Pop the last text domain.
     *
     * @throws Exception\RuntimeException
     * @return void
     */
    public function popTextDomain()
    {
        $translator = $this->getTranslator();

        if (null === $translator) {
            throw new Exception\RuntimeException('Translator has not been set');
        } elseif (!count($this->stack)) {
            throw new Exception\RuntimeException('No text domain found in the stack');
        }

        $translator->setDefaultTextDomain($this->stack->pop());
    }
}
