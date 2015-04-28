<?php

namespace MoviesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        return array('name' => $name);
    }

    /**
     * @Route("/info/on/movie/{movie}")
     * @Template()
     */
    public function infoonmovieAction($movie)
    {
        $movie = $this->get('api_service')->searchformovieAction($movie);

        return array('movie' => $movie);
    }
}
