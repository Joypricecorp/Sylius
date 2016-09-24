<?php

namespace Liverbool\Bundle\DummyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LiverboolDummyBundle:Default:index.html.twig');
    }
}
