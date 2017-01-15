<?php

namespace Vcare\Bundle\WebBundle\Model;

use Symfony\Cmf\Bundle\MediaBundle\FileInterface;
use Toro\Bundle\MediaBundle\Meta\MediaReference;

class ProductManual implements ProductManualInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var FileInterface
     */
    private $file;

    /**
     * @var string
     */
    private $fileId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var ProductTranslationInterface
     */
    private $productTranslation;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return FileInterface
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param FileInterface $file
     */
    public function setFile(FileInterface $file = null)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getFileId()
    {
        return $this->fileId;
    }

    /**
     * @param string $fileId
     */
    public function setFileId($fileId)
    {
        $this->fileId = $fileId;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return ProductTranslationInterface
     */
    public function getProductTranslation()
    {
        return $this->productTranslation;
    }

    /**
     * @param ProductTranslationInterface $productTranslation
     */
    public function setProductTranslation(ProductTranslationInterface $productTranslation = null)
    {
        $this->productTranslation = $productTranslation;
    }

    /**
     * {@inheritdoc}
     */
    public function getMediaMetaReferences()
    {
        return [
            new MediaReference(
                '/product-manuals/' . $this->productTranslation->getTranslatable()->getId(), 'fileId', $this->fileId, $this->file
            ),
        ];
    }
}
