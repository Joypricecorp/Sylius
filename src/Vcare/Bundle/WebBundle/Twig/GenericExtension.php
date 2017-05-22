<?php

namespace Vcare\Bundle\WebBundle\Twig;

class GenericExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('print_if', [$this, 'printIf']),
        ];
    }

    /**
     * @param string $text
     * @param boolean $condition
     *
     * @return string
     */
    public function printIf($text, $condition)
    {
        return $condition ? $text : '';
    }
}
