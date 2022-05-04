<?php

namespace Ndr\Label\Model\ResourceModel;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Ndr\Label\Model\ImageUploader;


class Label extends AbstractDb
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ndr_product_label', 'label_id');
    }

}
