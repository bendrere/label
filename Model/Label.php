<?php

namespace Ndr\Label\Model;


use Magento\CatalogRule\Model\Rule\Action\CollectionFactory;
use Magento\CatalogRule\Model\Rule\Condition\CombineFactory;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Rule\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json;
use Ndr\Label\Api\Data\LabelInterface;


class Label extends AbstractModel implements LabelInterface
{
    const STATUS_ENABLED = 1;

    const STATUS_DISABLED = 0;


    /**
     * @var CombineFactory
     */
    public CombineFactory $condCombineFactory;

    /**
     * @var CollectionFactory
     */
    public CollectionFactory $condProdCombineF;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param TimezoneInterface $localeDate
     * @param CombineFactory $condCombineFactory
     * @param CollectionFactory $condProdCombineF
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     * @param ExtensionAttributesFactory|null $extensionFactory
     * @param AttributeValueFactory|null $customAttributeFactory
     * @param Json|null $serializer
     */
    public function __construct(
        Context                    $context,
        Registry                   $registry,
        FormFactory                $formFactory,
        TimezoneInterface          $localeDate,
        CombineFactory             $condCombineFactory,
        CollectionFactory          $condProdCombineF,
        AbstractResource           $resource = null,
        AbstractDb                 $resourceCollection = null,
        array                      $data = [],
        ExtensionAttributesFactory $extensionFactory = null,
        AttributeValueFactory      $customAttributeFactory = null,
        Json                       $serializer = null
    )
    {
        $this->condCombineFactory = $condCombineFactory;
        $this->condProdCombineF = $condProdCombineF;
        parent::__construct(
            $context,
            $registry,
            $formFactory,
            $localeDate,
            $resource,
            $resourceCollection,
            $data,
            $extensionFactory,
            $customAttributeFactory,
            $serializer);
    }


    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Ndr\Label\Model\ResourceModel\Label');
        $this->setIdFieldName('label_id');
    }

    /**
     * @param string $formName
     * @return string
     */
    public function getConditionsFieldSetId(string $formName = ''): string
    {
        return $formName . 'rule_conditions_fieldset_' . $this->getId();
    }

    /**
     * @param $condition
     * @return Label
     */
    public function setConditionsSerialized($condition): Label
    {
        return $this->setData(self::CONDITIONS_SERIALIZED, $condition);
    }

    /**
     * @return mixed|null
     */
    public function getConditionsSerialized()
    {
        return $this->getData(self::CONDITIONS_SERIALIZED);
    }

    /**
     * @return mixed|null
     */
    public function getLabelImage()
    {
        return $this->getData(self::LABEL_IMAGE);
    }

    /**
     * @return \Magento\CatalogRule\Model\Rule\Condition\Combine|\Magento\Rule\Model\Condition\Combine
     */
    public function getConditionsInstance()
    {
        return $this->condCombineFactory->create();
    }

    /**
     * @return array
     */
    public function getAvailableStatuses(): array
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    /**
     * @param $label_image
     * @return Label
     */
    public function setLabelImage($label_image): Label
    {
        return $this->setData(self::LABEL_IMAGE, $label_image);
    }

    /**
     * @return \Magento\CatalogRule\Model\Rule\Action\Collection|\Magento\Rule\Model\Action\Collection
     */
    public function getActionsInstance()
    {
        return $this->condProdCombineF->create();
    }
}
