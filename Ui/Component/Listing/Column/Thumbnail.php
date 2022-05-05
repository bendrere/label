<?php

namespace Ndr\Label\Ui\Component\Listing\Column;


use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;


class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column
{

    /**
     *
     */
    const NAME = 'label_image';

    /**
     *
     */
    const ALT_FIELD = 'title';

    /**
     * @var string
     */
    protected $dir = 'ndr/label_image';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface                           $context,
        UiComponentFactory                         $uiComponentFactory,
        \Magento\Framework\UrlInterface            $urlBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array                                      $components = [],
        array                                      $data = []
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->_storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
    }


    /**
     * @param array $dataSource
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $mediaRelativePath = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->dir;
                $logoPath = $mediaRelativePath . '/' . $item['label_image'];
                $item[$fieldName . '_src'] = $logoPath;
                $item[$fieldName . '_alt'] = $this->getAlt($item);
                $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                    'ndr_label/label/edit',
                    ['label_id' => $item['label_id'], 'store' => $this->context->getRequestParam('store')]

                );
                $item[$fieldName . '_orig_src'] = $logoPath;

            }
        }
        return $dataSource;
    }

    /**
     * @param $row
     * @return null
     */
    protected function getAlt($row)
    {
        $altField = $this->getData('config/altField') ?: self::ALT_FIELD;
        return isset($row[$altField]) ? $row[$altField] : null;
    }
}
