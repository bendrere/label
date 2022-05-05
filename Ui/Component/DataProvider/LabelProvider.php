<?php

namespace Ndr\Label\Ui\Component\DataProvider;

use Magento\Store\Model\StoreManagerInterface;
use Ndr\Label\Model\ResourceModel\Label\CollectionFactory;

class LabelProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    /**
     * @var \Ndr\Label\Model\ResourceModel\Label\Collection
     */
    protected $collection;

    /**
     * @var
     */
    protected $loadedData;

    /**
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param CollectionFactory $pageCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $pageCollectionFactory,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $pageCollectionFactory->create();
        $this->storeManager = $storeManager;
        $this->meta = $this->prepareMeta($this->meta);
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );
    }

    /**
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta): array
    {
        return $meta;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        $this->loadedData = [];
        foreach ($items as $model) {
            $this->loadedData[$model->getId()] = $model->getData();
            if ($model->getLabelImage()) {
                $m['label_image'][0]['name'] = $model->getLabelImage();
                $m['label_image'][0]['url'] = $this->getMediaUrl() . $model->getLabelImage();
                $fullData = $this->loadedData;
                $this->loadedData[$model->getId()] = array_merge($fullData[$model->getId()], $m);
            }
        }

        return $this->loadedData;
    }


    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMediaUrl(): string
    {
        $mediaUrl = $this->storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'ndr/label_image/';
        return $mediaUrl;
    }
}
