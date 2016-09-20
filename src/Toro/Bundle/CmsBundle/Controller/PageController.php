<?php

namespace Toro\Bundle\CmsBundle\Controller;

use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Toro\Bundle\CmsBundle\Model\PageInterface;

class PageController extends ResourceController
{
    public function viewAction(Request $request)
    {
        // TODO: resource viewer
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        /** @var PageInterface $page */
        $page = $request->attributes->get('_sylius_entity');

        $this->eventDispatcher->dispatch(ResourceActions::SHOW, $configuration, $page);

        $view = View::create($page);
        $template = $page->getOptions() ? $page->getOptions()->getTemplate() : null;

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($template ?: $request->attributes->get('template', 'ToroCmsBundle::show.html.twig'))
                ->setTemplateVar($this->metadata->getName())
                ->setData([
                    //'configuration' => $configuration,
                    //'metadata' => $this->metadata,
                    //'resource' => $page,
                    $this->metadata->getName() => $page,
                ])
            ;
        }

        return $this->viewHandler->handle($configuration, $view);
    }
}
