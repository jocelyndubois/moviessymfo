<?php

namespace ApiBundle\Controller;

use MoviesBundle\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/searchformovie/{movietitle}")
     * @Template()
     */
    public function searchformovieAction($movietitle)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.allocine.fr/rest/v3/search?partner=YW5kcm9pZC12Mg&filter=movie,theater,person,news,tvseries&count=5&page=1&q='.urlencode($movietitle).'&format=json');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        // If using JSON...
        $data = json_decode($response, true);

        $movieInfos = $data['feed']['movie'][0];

        $fullInfosOnMovie = $this->getInfoForMovie($movieInfos['code']);

        $movie = new Movie();
        $movie->setCode($movieInfos['code']);
        $movie->setOriginalTitle($movieInfos['originalTitle']);
        if (isset($movieInfos['title'])) {
            $movie->setTitle($movieInfos['title']);
        } else {
            $movie->setTitle($movieInfos['originalTitle']);
        }
        $movie->setProductionYear($movieInfos['productionYear']);
        $movie->setSynopsis($fullInfosOnMovie['movie']['synopsis']);

        return $movie;
    }

    public function getInfoForMovie($codemovie)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.allocine.fr/rest/v3/movie?partner=YW5kcm9pZC12Mg&code='.$codemovie.'&profile=medium&mediafmt=mp4-lc&format=json&filter=movie&striptags=synopsis,synopsisshort');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        // If using JSON...
        $data = json_decode($response, true);
        return $data;
    }
}
