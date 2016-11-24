<?php

namespace ExpertCoder\TwigRendererBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ExpertCoderTwigRendererBundle:Default:index.html.twig');
    }
}
