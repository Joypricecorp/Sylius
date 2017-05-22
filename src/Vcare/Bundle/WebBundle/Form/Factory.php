<?php

namespace Vcare\Bundle\WebBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactory;

class Factory extends FormFactory
{
    private $pattern = '/dos_|sylius_/';
    private $replacement = 'vcare_';

    /**
     * @param string $pattern ereg pattern
     * @param string $replacement
     */
    public function setPrefixAndReplacement($pattern = null, $replacement = null)
    {
        $this->pattern = $pattern ?: $this->pattern;
        $this->replacement = $replacement ?: $this->replacement;
    }

    /**
     * {@inheritdoc}
     */
    public function createNamedBuilder($name, $type = FormType::class, $data = null, array $options = array())
    {
        if ($this->pattern) {
            $name = preg_replace($this->pattern, $this->replacement, $name);
        }

        return parent::createNamedBuilder($name, $type, $data, $options);
    }
}
