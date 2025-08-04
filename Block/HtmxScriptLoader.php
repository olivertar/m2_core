<?php
namespace Orangecat\Core\Block;

use Magento\Framework\View\Element\Template;
use Orangecat\Core\Helper\Data;

class HtmxScriptLoader extends Template
{
    protected $helper;

    public function __construct(
        Template\Context $context,
        Data $helper,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    public function isHtmxEnabled()
    {
        return $this->helper->isHtmxEnabled();
    }

    public function getHtmxScriptUrl()
    {
        return $this->helper->getHtmxScriptUrl();
    }
}
