<?php

namespace ApiBundle\Controller;

use MoviesBundle\Entity\Movie;
use MoviesBundle\Entity\Genre;
use MoviesBundle\Entity\MovieCast;
use MoviesBundle\Entity\Person;
use MoviesBundle\Entity\Video;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CallController extends Controller
{
    protected $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/searchformovie/{movietitle}")
     * @Template()
     */
    public function searchformovieAction($movietitle)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.themoviedb.org/3/search/movie?api_key=59993a697fab87df40343a36407af05f&language=fr&query=' . urlencode($movietitle));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        // If using JSON...
        $data = json_decode($response, true);

        if (!isset($data['results'][0])) {
            throw new \Exception('Film inconnu: ' . $movietitle);
        }

        $movieInfos = $data['results'][0];

        $movie = $this->em
            ->getRepository('MoviesBundle:Movie')
            ->findOneBy(array('code' => $movieInfos['id']));

        if (!$movie) {
            $fullInfosOnMovie = $this->getInfoForMovie($movieInfos['id']);

            $movie = new Movie();
            $movie->setCode($movieInfos['id']);
            $movie->setOriginalTitle($movieInfos['original_title']);
            $movie->setTitle($movieInfos['title']);
            $releaseDate = date_create_from_format('Y-m-d', $movieInfos['release_date']);
            $movie->setReleaseDate($releaseDate);
            $movie->setSynopsis($fullInfosOnMovie['overview']);
            $movie->setRuntime((int)$fullInfosOnMovie['runtime']);
            $movie->setPosterUrl($fullInfosOnMovie['poster_path']);

            $this->em->persist($movie);
            $this->em->flush();

            foreach ($fullInfosOnMovie['genres'] as $genreFromDb) {
                $genre = $this->em
                    ->getRepository('MoviesBundle:Genre')
                    ->findOneBy(array('code' => $genreFromDb['id']));

                if (!$genre) {
                    $genre = new Genre();
                    $genre->setCode($genreFromDb['id']);
                    $genre->setType($genreFromDb['name']);

                    $this->em->persist($genre);
                    $this->em->flush();
                }

                $movie->addGenre($genre);
            }

            $persons = $this->getPersonsForMovie($movie->getCode());

            foreach ($persons as $activity => $person) {
                if ($activity != 'id') {
                    foreach ($person as $pers) {
                        if ($activity == 'cast' || ($activity == 'crew' && $pers['job'] == 'Director')) {
                            $personObject = $this->em
                                ->getRepository('MoviesBundle:Person')
                                ->findOneBy(array('code' => $pers['id']));

                            if (!$personObject) {
                                $personObject = new Person();
                                $personObject->setCode($pers['id']);
                                $personObject->setName($pers['name']);
                                $personObject->setPictureUrl($pers['profile_path']);

                                $this->em->persist($personObject);
                                $this->em->flush();
                            }

                            if ($activity == 'cast') {
                                $movieCast = new MovieCast();
                                $movieCast->setMovie($movie);
                                $movieCast->setPerson($personObject);
                                $movieCast->setRole($pers['character']);
                                $movie->addCast($movieCast);
                            } elseif ($activity == 'crew' && $pers['job'] == 'Director') {
                                $movie->setDirector($personObject);
                            }
                        }
                    }
                }
            }

            $this->em->persist($movie);
            $this->em->flush();

            $videos = $this->getVideosForMovie($movie->getCode());
            $videos = array_merge($videos, $this->getVideosForMovie($movie->getCode(), 'fr'));

            foreach ($videos as $video) {
                $videoObject = $this->em
                    ->getRepository('MoviesBundle:Video')
                    ->findOneBy(array('code' => $video['key']));

                if (!$videoObject) {
                    $videoObject = new Video();
                    $videoObject->setName($video['name']);
                    $videoObject->setCode($video['key']);
                    $videoObject->setSite($video['site']);
                    $videoObject->setSize($video['size']);
                    $videoObject->setType($video['type']);
                    $videoObject->setLang($video['iso_639_1']);
                    $videoObject->setMovie($movie);

                    $this->em->persist($videoObject);
                    $this->em->flush();
                }

                $movie->addVideo($videoObject);
            }
        }

        $this->em->flush();

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
        curl_setopt($ch, CURLOPT_URL, 'http://api.themoviedb.org/3/movie/' . urlencode($codemovie) . '?api_key=59993a697fab87df40343a36407af05f&language=fr');
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
        curl_setopt($ch, CURLOPT_URL, 'http://api.themoviedb.org/3/movie/' . urlencode($codemovie) . '/credits?api_key=59993a697fab87df40343a36407af05f&language=fr');
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
    public function getVideosForMovie($codemovie, $language = 'en')
    {
        $ch = curl_init();
        $url = 'http://api.themoviedb.org/3/movie/' . urlencode($codemovie) . '/videos?api_key=59993a697fab87df40343a36407af05f';
        if ($language) {
            $url .= "&language=$language";
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        // If using JSON...
        $data = json_decode($response, true);
        return $data['results'];
    }
}
