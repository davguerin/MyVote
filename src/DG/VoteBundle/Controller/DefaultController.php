<?php

namespace DG\VoteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DGVoteBundle:Default:index.html.twig', array('name' => $name));
    }
}
