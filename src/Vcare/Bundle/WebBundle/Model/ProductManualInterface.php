<?php

namespace Vcare\Bundle\WebBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Cmf\Bundle\MediaBundle\FileInterface;
use Toro\Bundle\MediaBundle\Model\MediaAwareInterface;

interface ProductManualInterface extends ResourceInterface, MediaAwareInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return FileInterface
     */
    public function getFile();

    /**
     * @param FileInterface $file
     */
    public function setFile(FileInterface $file = null);

    /**
     * @return string
     */
    public function getFileId();

    /**
     * @param string $fileId
     */
    public function setFileId($fileId);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     */
    public function setTitle($title);

    /**
     * @return ProductTranslationInterface
     */
    public function getProductTranslation();

    /**
     * @param ProductTranslationInterface $productTranalation
     */
    public function setProductTranslation(ProductTranslationInterface $productTranalation = null);
}
