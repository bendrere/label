<?php

namespace Ndr\Label\Model\ResourceModel\Label;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'label_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'label_label_collection';

    /**
     * @var string
     */
    protected $_eventObject = 'label_collection';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ndr\Label\Model\Label', 'Ndr\Label\Model\ResourceModel\Label');
    }
}
