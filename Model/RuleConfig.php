<?php

namespace Ndr\Label\Model;

use Magento\Store\Model\StoreManagerInterface;
use Ndr\Label\Model\ResourceModel\Label\CollectionFactory;
use Magento\Customer\Model\Session;

class RuleConfig
{

    /**
     * @param CollectionFactory $collectionFactory
     * @param StoreManagerInterface $storeManager
     * @param Session $customerSession
     * @param Label $model
     * @param LabelFactory $label
     */
    public function __construct(
        CollectionFactory     $collectionFactory,
        StoreManagerInterface $storeManager,
        Session               $customerSession,
        Label                 $model,
        LabelFactory          $label
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
        $this->model = $model;
        $this->label = $label;
    }

    /**
     * @param $_product
     * @return int
     */
    public function getLastAppiledRuleId($_product)
    {

        $ruleCollection = $this->collectionFactory->create()
            ->addFieldToSelect('*');

        $ruleId = 0;
        if (!empty($_product)) {
            foreach ($ruleCollection as $rule) {
                $matchedProductIds = [];
                if (!empty($rule->getConditionsSerialized())) {
                    $matchedProductId = $this->label->create()->setData('conditions_serialized', $rule->getConditionsSerialized());

                    if ($matchedProductId->getConditions()->validate($_product)) {
                        $matchedProductIds[] = $_product->getId();
                    }
                } else {
                    $matchedProductIds[] = $_product->getId();

                }
                if (!empty($matchedProductIds) && in_array($_product->getId(), $matchedProductIds)) {
                    $ruleId = $rule->getId();
                }
            }
        }
        return $ruleId;
    }

    /**
     * @param $rule_id
     * @return mixed|void|null
     */
    public function getLabelImage($rule_id)
    {
        if (isset($rule_id) & !empty($rule_id)) {
            $this->model->load($rule_id);
            return $this->model->getData('label_image');
        }
    }
}
