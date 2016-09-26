<?php

namespace Toro\Bundle\CmsBundle\Controller;

use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Toro\Bundle\CmsBundle\Model\OptionableInterface;
use Toro\Bundle\CmsBundle\Model\PageInterface;

class PageController extends ResourceController
{
    public function viewAction(Request $request, $entity = null)
    {
        // TODO: resource viewer

        /** @var OptionableInterface|TimestampableInterface|ResourceInterface $page */
        $page = $entity ?: $request->attributes->get('_toro_entity');

        if (!$page) {
            throw new NotFoundHttpException("No page found");
        }

        $template = null;
        $templateStrategy = null;
        $templateContent = null;
        $templateVar = $this->metadata->getName();

        // FIXME: with some cool idea!
        if ($page instanceof PageInterface) {
            if ($request->getBaseUrl() !== '/app_dev.php') {
                $page->setBody(
                    preg_replace('|/app_dev.php|', $request->getBaseUrl(), $page->getBody())
                );
            }
        }

        if ($option = $page->getOptions()) {
            $template = $option->getTemplate();
            $templateStrategy = $option->getTemplateStrategy();
            $templateVar = $option->getTemplateVar($templateVar);
        }

        if ('blank' === $templateStrategy && empty($template)) {
            $template = 'ToroCmsBundle::blank.html.twig';
        }

        if ('partial' === $templateStrategy && empty($template)) {
            $template = 'ToroCmsBundle::partial.html.twig';
        }

        if (!$template) {
            $template = $request->attributes->get('template', 'ToroCmsBundle::show.html.twig');
        }

        if ($option) {
            $twig = $this->get('twig');
            $cache = $twig->getCache(false);

            // compile template ondemand
            if ($option->isNeedToCompile()) {
                $templating = $option->getTemplating();;
                $compiled = $twig->compileSource($templating);
                $key = $cache->generateKey(null, get_class($page));

                $cache->write($key, $compiled);
                $option->setCompiled($key);

                $this->get('toro.manager.option')->flush();
            }

            if ($key = $option->getCompiled()) {
                $twig->getCache(false)->load($key);
                $classes = get_declared_classes();
                $class = end($classes);

                /** @var \Twig_Template $compliedTemplate */
                $compliedTemplate = new $class($twig);
                $templateContent = $compliedTemplate->render([
                    $page->getOptions()->getTemplateVar() => $page,
                ]);
            }
        }

        $view = View::create($page);

        $view
            ->setTemplate($template)
            ->setData([
                'template_strategy' => $templateStrategy,
                'template_content' => $templateContent,
                $templateVar => $page,
            ])
        ;

        // TODO: support api request
        return $this->get('fos_rest.view_handler')->handle($view);
    }
}
