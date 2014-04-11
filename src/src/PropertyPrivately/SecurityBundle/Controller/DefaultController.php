<?php

namespace PropertyPrivately\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PropertyPrivatelySecurityBundle:Default:index.html.twig', array('name' => $name));
    }
}
