<?php

namespace MoviesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

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
        $movie = $this->setLanguagesVideosForMovie($movie);

        return array(
            'movie' => $movie
        );
    }

    private function setLanguagesVideosForMovie($Movie)
    {
        $frVideos = array();
        $enVideos = array();

        if (count($Movie->getVideos()) > 1) {
            foreach ($Movie->getVideos() as $videos) {
                if ($videos->getLang() == 'en') {
                    $enVideos[] = $videos;
                } elseif ($videos->getLang() == 'fr') {
                    $frVideos[] = $videos;
                }
            }
        }

        $Movie->setFrVideos($frVideos);
        $Movie->setEnVideos($enVideos);

        return $Movie;
    }

    /**
     * @Route("/all/movies", name="_allMovies")
     * @Secure(roles="ROLE_USER")
     * @Template()
     */
    public function showAllMoviesAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $movies = $user->getMovies();

        $genres = array();
        foreach ($movies as $movie) {
            $movie = $this->setLanguagesVideosForMovie($movie);
            foreach ($movie->getGenres() as $genre) {
                if (!in_array($genre, $genres)) {
                    $genres[] = $genre;
                }
            }
        }
        foreach ($movies as $movie) {
            foreach ($movie->getGenres() as $genre) {
                if (!in_array($genre, $genres)) {
                    $genres[] = $genre;
                }
            }
        }

        return array(
            'movies' => $movies,
            'genres' => $genres
        );
    }
}
