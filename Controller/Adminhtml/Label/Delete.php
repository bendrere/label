<?php

namespace Ndr\Label\Controller\Adminhtml\Label;

use Magento\Backend\App\Action;
use Ndr\Label\Model\Label;

class Delete extends Action
{

    /**
     * @var Label
     */
    protected $model;

    /**
     * @param Action\Context $context
     * @param Label $model
     */
    public function __construct(
        Action\Context $context,
        Label          $model
    )
    {
        $this->model = $model;
        parent::__construct($context);
    }


    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            try {
                $this->model->load($id);
                $this->model->delete();
                $this->messageManager->addSuccess(__('Label has been deleted successfully.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find label to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
