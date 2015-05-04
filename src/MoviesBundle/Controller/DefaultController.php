<?php

namespace MoviesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="_homepage")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/search", name="_searchmovie")
     * @Template()
     */
    public function searchmovieAction()
    {
        return $this->redirect($this->generateUrl('_infoonmovie', array('movie' => $this->getRequest()->get('movie'))));
    }

    /**
     * @Route("/info/on/movie/{movie}", name="_infoonmovie")
     * @Template()
     */
    public function infoonmovieAction($movie)
    {
        $movie = $this->get('api_service')->searchformovieAction($movie);

        $frVideos = array();
        $enVideos = array();

        if (count($movie->getVideos()) > 1) {
            foreach ($movie->getVideos() as $videos) {
                if ($videos->getLang() == 'en') {
                    $enVideos[] = $videos;
                } elseif ($videos->getLang() == 'fr') {
                    $frVideos[] = $videos;
                }
            }
        }

        return array(
            'movie' => $movie,
            'frVideos' => $frVideos,
            'enVideos' => $enVideos
        );
    }

    /**
     * @Route("/all/movies", name="_allMovies")
     * @Template()
     */
    public function showAllMoviesAction()
    {
        $movies = $this->getDoctrine()->getEntityManager()
            ->getRepository('MoviesBundle:Movie')
            ->findAll();

        $genres = $this->getDoctrine()->getEntityManager()
            ->getRepository('MoviesBundle:Genre')
            ->findAll();

        return array(
            'movies' => $movies,
            'genres' => $genres
        );
    }
}
