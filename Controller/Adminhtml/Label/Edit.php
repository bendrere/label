<?php

namespace Ndr\Label\Controller\Adminhtml\Label;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\Session;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Ndr\Label\Model\Label;


class Edit extends \Magento\Backend\App\Action
{

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Label
     */
    protected $model;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var
     */
    private $data;

    /**
     * @var Registry
     */
    private $_coreRegistry;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param Session $session
     * @param Label $model
     */
    public function __construct(
        Context     $context,
        Registry    $coreRegistry,
        PageFactory $resultPageFactory,
        Session     $session,
        Label       $model
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->model = $model;
        $this->_coreRegistry = $coreRegistry;
        $this->session = $session;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface|mixed
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $id = $this->getRequest()->getParam('id');

        if ($id) {
            $this->model->load($id);
            if (!$this->model->getData('label_id')) {
                $this->messageManager->addError(__('This rule is no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
            $this->model->getConditions()->setJsFormObject('ndr_label_form');

        }

        $data = $this->model->getData();


        if (!empty($data)) {
            $this->model->setData($data);
        }


        $this->_coreRegistry->register('Ndr_Label', $this->model);

        $resultPage = $this->initPage($resultPage);
        $resultPage->addBreadcrumb(
            $id ? __('Edit Label') : __('New Label'),
            $id ? __('Edit Label') : __('New Label')
        );
        $resultPage->getConfig()->getTitle()
            ->prepend($this->model->
            getId() ? $this->model->getName() : __('New Label'));
        return $resultPage;
    }

    /**
     * @param $resultPage
     * @return mixed
     */
    protected function initPage($resultPage)
    {
        $resultPage->setActiveMenu('Ndr_Label::ndr_label')
            ->addBreadcrumb(__('Ndr'), __('Product Labels'));

        return $resultPage;
    }
}
