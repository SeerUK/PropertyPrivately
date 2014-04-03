<?php

namespace SeerUK\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SeerUKRestBundle:Default:index.html.twig', array('name' => $name));
    }
}
