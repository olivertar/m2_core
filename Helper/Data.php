<?php

namespace Orangecat\Core\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    const GENERAL_HTMX_XML_PATH_ENABLED     = 'orangecat/htmx/enabled';
    const GENERAL_HTMX_XML_PATH_URL         = 'orangecat/htmx/script_url';

    const GENERAL_MAPS_XML_PATH_APIKEY      = 'orangecat/maps/map_api_key';
    const GENERAL_MAPS_XML_PATH_STYLE       = 'orangecat/maps/map_style';

    public $encryptor;

    public $session;

    public $httpContext;

    public $serialize;

    /**
     * Constructor
     *
     * @param Context $context
     */
    public function __construct(
        Context $context
    )
    {
        $this->_init();
        parent::__construct($context);
    }

    private function _init()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->encryptor = $objectManager->create(\Magento\Framework\Encryption\EncryptorInterface::class);
        $this->session = $objectManager->get(\Magento\Customer\Model\Session::class);
        $this->httpContext = $objectManager->get(\Magento\Framework\App\Http\Context::class);
        $this->serialize = $objectManager->get(\Magento\Framework\Serialize\Serializer\Json::class);
    }

    /**
     *  Get Config
     *  This method obtains a value from system and returns it
     *
     * @param String $path
     *
     * @return mixed
     */
    public function getConfig($path)
    {
        $value = $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);

        return $value;
    }

    /**
     *  Get Yes/No
     *  This method obtains a value of type YES/NO from system and returns it as true/false
     *
     * @param String $path
     *
     * @return bool
     */
    public function getYesNo($path)
    {
        $value = $this->scopeConfig->isSetFlag($path, ScopeInterface::SCOPE_STORE);

        return $value;
    }

    /**
     *  Get Encrypted
     *  This method obtains Encrypted values from system and returns it decrypted
     * <backend_model>Magento\Config\Model\Config\Backend\Encrypted</backend_model>
     *
     * @param String $path
     *
     * @return string
     */
    public function getEncrypted($path)
    {
        $value = $this->getConfig($path);

        return $this->encryptor->decrypt($value);
    }

    /**
     *  Is Logged in
     *  This method return true if user is logedd in
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        if ($this->session->isLoggedIn()){
            return true;
        }

        return false;
    }

    public function isLoggedInContext()
    {
        $isLoggedIn = $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);

        return $isLoggedIn;
    }

    public function isHtmxEnabled()
    {
        return $this->getYesNo(self::GENERAL_HTMX_XML_PATH_ENABLED);
    }

    public function getHtmxScriptUrl()
    {
        return $this->getConfig(self::GENERAL_HTMX_XML_PATH_URL);
    }

    public function isBreeze()
    {
        $design = \Magento\Framework\App\ObjectManager::getInstance()->get(\Magento\Framework\View\DesignInterface::class);
        $themeCode = $design->getDesignTheme()->getCode();
        if (strpos($themeCode, 'breeze')) {
            return true;
        }

        return false;
    }

    public function getMapsApiKey()
    {
        return $this->getConfig(self::GENERAL_MAPS_XML_PATH_APIKEY);
    }

    public function getMapsStyle()
    {
        return $this->getConfig(self::GENERAL_MAPS_XML_PATH_STYLE);
    }
}
