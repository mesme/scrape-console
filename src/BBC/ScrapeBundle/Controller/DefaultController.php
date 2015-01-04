<?php

namespace BBC\ScrapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ScrapeBundle:Default:index.html.twig', array('name' => $name));
    }
}
