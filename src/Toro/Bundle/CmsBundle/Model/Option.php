<?php

namespace Toro\Bundle\CmsBundle\Model;

use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\TranslatableInterface;

abstract class Option implements OptionInterface
{
    use TimestampableTrait;
    use BlameableTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var string
     */
    protected $templating;

    /**
     * @var \DateTime
     */
    protected $templatingChangedAt;

    /**
     * @var string
     */
    protected $compiled;

    /**
     * @var \DateTime
     */
    protected $compiledAt;

    /**
     * @var string
     */
    protected $template;

    public function __construct()
    {
        $this->templatingChangedAt = new \DateTime();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplating()
    {
        return $this->templating;
    }

    /**
     * {@inheritdoc}
     */
    public function setTemplating($templating)
    {
        $this->templating = $templating;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplatingChangedAt()
    {
        return $this->templatingChangedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setTemplatingChangedAt(\DateTime $templatingChangedAt)
    {
        $this->templatingChangedAt = $templatingChangedAt;
    }

    /**
     * To support translatable model.
     *
     * @return string|void
     */
    private function getCurrentLocale()
    {
        if ($this->getOptionable() instanceof TranslatableInterface) {
            /** @var TranslatableInterface $translatable */
            $translatable = $this->getOptionable();

            return $translatable->translate()->getLocale();
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function getCompiled()
    {
        if ($currentLocale = $this->getCurrentLocale()) {
            $data = json_decode($this->compiled, true) ?: [$currentLocale => ''];

            return isset($data[$currentLocale]) ? $data[$currentLocale] : '';
        }

        return $this->compiled;
    }

    /**
     * {@inheritdoc}
     */
    public function setCompiled($compiled)
    {
        if ($currentLocale = $this->getCurrentLocale()) {
            $data = json_decode($this->compiled, true) ?: [];
            $data[$currentLocale] = $compiled;

            $compiled = json_encode($data);
        }

        $this->compiled = $compiled;
    }

    /**
     * {@inheritdoc}
     */
    public function getCompiledAt()
    {
        return $this->compiledAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setCompiledAt(\DateTime $compiledAt)
    {
        $this->compiledAt = $compiledAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * {@inheritdoc}
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }
}
