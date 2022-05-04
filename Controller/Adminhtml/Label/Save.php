<?php

namespace Ndr\Label\Controller\Adminhtml\Label;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Serialize\SerializerInterface;
use Ndr\Label\Model\ImageUploader;
use Ndr\Label\Model\Label;


class Save extends \Magento\Backend\App\Action
{

    /**
     * @var Label
     */
    protected $model;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param Context $context
     * @param Label $model
     * @param SerializerInterface $serializer
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        Context             $context,
        Label               $model,
        SerializerInterface $serializer,
        ImageUploader       $imageUploader
    )
    {
        $this->model = $model;
        $this->serializer = $serializer;
        $this->imageUploader = $imageUploader;
        parent::__construct($context);
    }


    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $data = $this->prepareData($data);
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            if (isset($data['rule'])) {
                $data['conditions'] = $data['rule']['conditions'];
                unset($data['rule']);
            }
            $data['updation_time'] = date('Y-m-d h:i:s');
            unset($data['conditions_serialized']);
            unset($data['actions_serialized']);

            $data['condition'] = $this->serializer->serialize($data['conditions']);

            if (isset($data['label_image'])) {
                if (isset($data['label_image'][0]['name']) && isset($data['label_image'][0]['tmp_name'])) {
                    $data['label_image'] = $data['label_image'][0]['name'];
                    $this->imageUploader->moveFileFromTmp($data['label_image']);
                } elseif (isset($data['label_image'][0]['name']) && !isset($data['label_image'][0]['tmp_name'])) {
                    $data['label_image'] = $data['label_image'][0]['name'];
                }
            }
            $this->model->loadPost($data);

            try {
                $this->model->setData($data)->save();
                $this->messageManager->addSuccess(__('Label are saved successfully.'));
                if ($this->getRequest()->getParam('back')) {
                    if (!empty($data['label_id'])) {
                        return $resultRedirect->setPath(
                            '*/*/edit',
                            ['id' => $data['label_id'],
                                '_current' => true]
                        );
                    } else {
                        return $resultRedirect->setPath(
                            '*/*/edit',
                            ['_current' => true]
                        );
                    }
                }
                return $resultRedirect->setPath('*/*/');

            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the rule.'));
                $this->messageManager->addException($e, $e->getMessage());
            }

            if (!empty($data['label_id'])) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $data['label_id']]);
            } else {
                return $resultRedirect->setPath('*/*/edit');
            }
        }
        return $resultRedirect->setPath('*/*/');
    }


    /**
     * @param $data
     * @return mixed
     */
    protected function prepareData($data)
    {
        if (isset($data['rule']['conditions'])) {
            $data['conditions'] = $data['rule']['conditions'];
        }
        unset($data['rule']);
        return $data;
    }

}

