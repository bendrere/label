<?php

namespace Ndr\Label\Ui\Component\Listing\Column;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class LabelActions extends Column
{

    /**
     *edit
     */
    const URL_PATH_EDIT = 'ndr_label/label/edit';
    /**
     *delete
     */
    const URL_PATH_DELETE = 'ndr_label/label/delete';

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;


    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(ContextInterface   $context,
                                UiComponentFactory $uiComponentFactory,
                                UrlInterface       $urlBuilder,
                                array              $components = [],
                                array              $data = [])
    {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }


    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['label_id'])) {
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_EDIT,
                                [
                                    'id' => $item['label_id']
                                ]
                            ),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_DELETE,
                                [
                                    'id' => $item['label_id']
                                ]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete %1'),
                                'message' => __('Are you sure you wan\'t to delete a %1 record?'),
                            ],
                        ],
                    ];
                }
            }
        }

        return $dataSource;
    }

}
