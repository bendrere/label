<?php

namespace Ndr\Label\Helper;

use Magento\Framework\App\Helper\Context;
use Ndr\Label\Model\RuleConfig;

class LabelHelper extends \Magento\Framework\Url\Helper\Data
{

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param RuleConfig $rules
     */
    public function __construct(
        Context                                    $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        RuleConfig                                 $rules
    )
    {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->rules = $rules;

    }

    /**
     * @param $product
     * @return mixed|string|void|null
     */
    public function getLabelImage($product)
    {
        $ruleId = $this->rules->getLastAppiledRuleId($product);
        if (!empty($product->getData('label_image'))) {
            return $product->getData('label_image');
        } else if ($ruleId) {
            if (!empty($this->rules->getLabelImage($ruleId))) {
                return $this->rules->getLabelImage($ruleId);
            } else {
                return "";
            }
        } else {
            return "";
        }
    }


    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMediaUrl()
    {
        $mediaUrl = $this->storeManager
            ->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl . "ndr/label_image/";
    }

}
