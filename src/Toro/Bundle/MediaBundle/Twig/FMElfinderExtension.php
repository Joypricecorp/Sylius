<?php

namespace Toro\Bundle\MediaBundle\Twig;

use Stfalcon\Bundle\TinymceBundle\Twig\Extension\StfalconTinymceExtension;

class FMElfinderExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var StfalconTinymceExtension
     */
    protected $tinymce;

    public function __construct(\Twig_Environment $twig, StfalconTinymceExtension $tinymce = null)
    {
        $this->twig = $twig;
        $this->tinymce = $tinymce;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        $options = array('is_safe' => array('html'));

        return array(
            new \Twig_SimpleFunction('toro_tinymce', array($this, 'tinymce4'), $options),
        );
    }

    /**
     * @param array $parameters
     * @param string $instance
     *
     * @throws \Twig_Error_Runtime
     *
     * @return mixed
     */
    public function tinymce4($parameters = array(), $instance = 'default') {

        if (!is_string($instance)) {
            throw new \Twig_Error_Runtime('The function can be applied to strings only.');
        }

        $parameters = array_merge(
            ['width' => 900, 'height' => 450, 'title' => 'Toro File Manager', 'mediaPath' => null, 'tinymce' => []],
            $parameters
        );

        // https://github.com/stfalcon/TinymceBundle/issues/180
        $parameters['tinymce'] = array_merge(['asset_package_name' => 'toromce44'], $parameters['tinymce']);

        return $this->twig->render('ToroMediaBundle:Elfinder:_tinymce4.html.twig', array(
                'instance' => $instance,
                'width' => $parameters['width'],
                'height' => $parameters['height'],
                'title' => $parameters['title'],
                'homeFolder' => $parameters['mediaPath'],
            )
        ) . $this->tinymce->tinymceInit($parameters['tinymce']);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'toro_el_finder';
    }
}
