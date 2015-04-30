<?php

namespace ApiBundle\Controller;

use MoviesBundle\Entity\Movie;
use MoviesBundle\Entity\Genre;
use MoviesBundle\Entity\Person;
use MoviesBundle\Entity\Video;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CallController extends Controller
{
    /**
     * @Route("/searchformovie/{movietitle}")
     * @Template()
     */
    public function searchformovieAction($movietitle)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.themoviedb.org/3/search/movie?api_key=59993a697fab87df40343a36407af05f&language=fr&query='.urlencode($movietitle));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        // If using JSON...
        $data = json_decode($response, true);

        if (!isset($data['results'][0])) {
            throw new \Exception('Film inconnu: '.$movietitle);
        }

        $movieInfos = $data['results'][0];

        $fullInfosOnMovie = $this->getInfoForMovie($movieInfos['id']);

        $movie = new Movie();
        $movie->setCode($movieInfos['id']);
        $movie->setOriginalTitle($movieInfos['original_title']);
        $movie->setTitle($movieInfos['title']);
        $movie->setReleaseDate($movieInfos['release_date']);
        $movie->setSynopsis($fullInfosOnMovie['overview']);
        $movie->setRuntime((int)$fullInfosOnMovie['runtime']);
        $movie->setPosterUrl($fullInfosOnMovie['poster_path']);

        foreach ($fullInfosOnMovie['genres'] as $genreFromDb) {
            $genre = new Genre();
            $genre->setCode($genreFromDb['id']);
            $genre->setType($genreFromDb['name']);

            $movie->addGenre($genre);
        }

        $persons = $this->getPersonsForMovie($movie->getCode());

        foreach ($persons as $activity => $person) {
            if ($activity != 'id') {
                foreach ($person as $pers) {
                    $personObject = new Person();
                    $personObject->setCode($pers['id']);
                    $personObject->setName($pers['name']);
                    $personObject->setPictureUrl($pers['profile_path']);
                    if ($activity == 'cast') {
                        $movie->addCast($personObject);
                    } elseif ($activity == 'crew') {
                        $movie->addCrew($personObject);
                    }
                }
            }
        }

        foreach ($this->getVideosForMovie($movie->getCode()) as $video) {
            $videoObject = new Video();
            $videoObject->setName($video['name']);
            $videoObject->setCode($video['key']);
            $videoObject->setSite($video['site']);
            $videoObject->setSize($video['size']);
            $videoObject->setType($video['type']);
            $movie->addVideo($videoObject);
        }

        return $movie;
    }

    /**
     * Retourne les infos précises pour un film donné
     *
     * @param $codemovie
     * @return mixed
     */
    public function getInfoForMovie($codemovie)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.themoviedb.org/3/movie/'.urlencode($codemovie).'?api_key=59993a697fab87df40343a36407af05f&language=fr');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        // If using JSON...
        $data = json_decode($response, true);
        return $data;
    }

    /**
     * Retourne le staff pour un film donné
     *
     * @param $codemovie
     * @return mixed
     */
    public function getPersonsForMovie($codemovie)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.themoviedb.org/3/movie/'.urlencode($codemovie).'/credits?api_key=59993a697fab87df40343a36407af05f&language=fr');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        // If using JSON...
        $data = json_decode($response, true);
        return $data;
    }

    /**
     * Retourne les vidéos pour un film donné
     *
     * @param $codemovie
     * @return mixed
     */
    public function getVideosForMovie($codemovie)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.themoviedb.org/3/movie/'.urlencode($codemovie).'/videos?api_key=59993a697fab87df40343a36407af05f&language=fr');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        // If using JSON...
        $data = json_decode($response, true);
        return $data['results'];
    }
}
