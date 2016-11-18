<?php

namespace OCUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/security")
     */
    public function indexAction()
    {
        return $this->render('EmauxBundle:Default:index.html.twig');
    }
}
