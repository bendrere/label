<?php

namespace Ndr\Label\Model\Product\Attribute\Backend;

use Magento\Framework\App\Filesystem\DirectoryList;


class File extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    protected $_file;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_filesystem;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $_fileUploaderFactory;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Filesystem\Driver\File $file
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\UrlInterface $urlInterface
     * @param \Magento\Framework\App\Request\Http $request
     */
    public function __construct(
        \Psr\Log\LoggerInterface                         $logger,
        \Magento\Framework\Filesystem                    $filesystem,
        \Magento\Framework\Filesystem\Driver\File        $file,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\Message\ManagerInterface      $messageManager,
        \Magento\Store\Model\StoreManagerInterface       $storeManager,
        \Magento\Framework\UrlInterface                  $urlInterface,
        \Magento\Framework\App\Request\Http              $request
    )
    {
        $this->_file = $file;
        $this->_filesystem = $filesystem;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_logger = $logger;
        $this->messageManager = $messageManager;
        $this->_storeManager = $storeManager;
        $this->_urlInterface = $urlInterface;
        $this->request = $request;
    }

    /**
     * @param $object
     * @return $this|File|void
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function afterSave($object)
    {
        if ($this->request->getModuleName() != 'ndr_Label') {
            $path = $this->_filesystem->getDirectoryRead(
                DirectoryList::MEDIA
            )->getAbsolutePath(
                'ndr/label_image/'
            );

            $delete = $object->getData($this->getAttribute()->getName() . '_delete');

            if ($delete) {
                $fileName = $object->getData($this->getAttribute()->getName());

                $object->setData($this->getAttribute()->getName(), '');
                $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
                if ($this->_file->isExists($path . $fileName)) {
                    $this->_file->deleteFile($path . $fileName);
                }
            }

            try {
                $uploader = $this->_fileUploaderFactory
                    ->create(['fileId' => 'product[' . $this->getAttribute()->getName() . ']']);
                $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                $uploader->setAllowRenameFiles(true);
                $result = $uploader->save($path);
                $object->setData($this->getAttribute()->getName(), $result['file']);
                $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
            } catch (\Exception $e) {
                if ($e->getCode() != \Magento\MediaStorage\Model\File\Uploader::TMP_NAME_EMPTY) {
                    $this->messageManager
                        ->addError(
                            $e
                                ->getMessage() . " Please upload product label image with extensions jpg , jpeg , gif , png only."
                        );
                    $this->_logger->critical($e);
                }
            }

            return $this;
        }
    }
}
