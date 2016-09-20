<?php

namespace Ishmael\Bundle\DummyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('IshmaelDummyBundle:Default:index.html.twig');
    }
}
