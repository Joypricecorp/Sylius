<?php

namespace Toro\Bundle\CmsBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;

interface OptionInterface extends ResourceInterface
{
    /**
     * @return array
     */
    public function getData();

    /**
     * @param array $data
     */
    public function setData($data);

    /**
     * @return mixed
     */
    public function getTemplating();

    /**
     * @param mixed $templating
     */
    public function setTemplating($templating);

    /**
     * @return \DateTime
     */
    public function getTemplatingChangedAt();

    /**
     * @param \DateTime $templatingChangedAt
     */
    public function setTemplatingChangedAt(\DateTime $templatingChangedAt);

    /**
     * @return string
     */
    public function getCompiled();

    /**
     * @param string $compiled
     */
    public function setCompiled($compiled);

    /**
     * @return \DateTime
     */
    public function getCompiledAt();

    /**
     * @param \DateTime $compiledAt
     */
    public function setCompiledAt(\DateTime $compiledAt);

    /**
     * @return string
     */
    public function getTemplate();

    /**
     * @param string $template
     */
    public function setTemplate($template);

    /**
     * @return OptionableInterface
     */
    public function getOptionable();

    /**
     * @param OptionableInterface|null $optionable
     */
    public function setOptionable(OptionableInterface $optionable = null);
}
