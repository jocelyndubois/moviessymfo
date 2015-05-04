<?php

namespace MoviesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Movie
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MoviesBundle\Entity\MovieRepository")
 */
class Movie
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, unique=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="originalTitle", type="string", length=255)
     */
    private $originalTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="releaseDate", type="date")
     */
    private $releaseDate;

    /**
     * @var string
     *
     * @ORM\Column(name="synopsis", type="string", length=4000, nullable=true)
     */
    private $synopsis;

    /**
     * @var integer
     *
     * @ORM\Column(name="runtime", type="integer", nullable=true)
     */
    private $runtime;

    /**
     * @var string
     *
     * @ORM\Column(name="posterUrl", type="string", length=255, nullable=true)
     */
    private $posterUrl;

    /**
     * @ORM\ManyToMany(targetEntity="Genre", inversedBy="movies")
     * @ORM\JoinTable(name="movies_genres")
     */
    private $genres;

    /**
     * @ORM\OneToMany(targetEntity="MovieCast", mappedBy="movie", cascade={"remove", "persist"})
     */
    private $cast;

    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="director")
     * @ORM\JoinColumn(name="director", referencedColumnName="id")
     */
    private $director;

    /**
     * @ORM\OneToMany(targetEntity="Video", mappedBy="movie", cascade={"remove", "persist"})
     */
    private $videos;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Movie
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set originalTitle
     *
     * @param string $originalTitle
     * @return Movie
     */
    public function setOriginalTitle($originalTitle)
    {
        $this->originalTitle = $originalTitle;

        return $this;
    }

    /**
     * Get originalTitle
     *
     * @return string 
     */
    public function getOriginalTitle()
    {
        return $this->originalTitle;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Movie
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set synopsis
     *
     * @param string $synopsis
     * @return Movie
     */
    public function setSynopsis($synopsis)
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    /**
     * Get synopsis
     *
     * @return string 
     */
    public function getSynopsis()
    {
        return $this->synopsis;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->genres = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add genres
     *
     * @param \MoviesBundle\Entity\Genre $genres
     * @return Movie
     */
    public function addGenre(\MoviesBundle\Entity\Genre $genres)
    {
        $this->genres[] = $genres;

        return $this;
    }

    /**
     * Remove genres
     *
     * @param \MoviesBundle\Entity\Genre $genres
     */
    public function removeGenre(\MoviesBundle\Entity\Genre $genres)
    {
        $this->genres->removeElement($genres);
    }

    /**
     * Get genres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGenres()
    {
        return $this->genres;
    }

    /**
     * Add persons
     *
     * @param \MoviesBundle\Entity\Person $persons
     * @return Movie
     */
    public function addPerson(\MoviesBundle\Entity\Person $persons)
    {
        $this->persons[] = $persons;

        return $this;
    }

    /**
     * Remove persons
     *
     * @param \MoviesBundle\Entity\Person $persons
     */
    public function removePerson(\MoviesBundle\Entity\Person $persons)
    {
        $this->persons->removeElement($persons);
    }

    /**
     * Get persons
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPersons()
    {
        return $this->persons;
    }
    

    /**
     * Set releaseDate
     *
     * @param \DateTime $releaseDate
     * @return Movie
     */
    public function setReleaseDate($releaseDate)
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    /**
     * Get releaseDate
     *
     * @return \DateTime 
     */
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    /**
     * Set runtime
     *
     * @param integer $runtime
     * @return Movie
     */
    public function setRuntime($runtime)
    {
        $this->runtime = $runtime;

        return $this;
    }

    /**
     * Get runtime
     *
     * @return integer 
     */
    public function getRuntime()
    {
        return $this->runtime;
    }

    /**
     * Set posterUrl
     *
     * @param string $posterUrl
     * @return Movie
     */
    public function setPosterUrl($posterUrl)
    {
        $this->posterUrl = $posterUrl;

        return $this;
    }

    /**
     * Get posterUrl
     *
     * @return string 
     */
    public function getPosterUrl()
    {
        return $this->posterUrl;
    }

    /**
     * Add videos
     *
     * @param \MoviesBundle\Entity\Video $videos
     * @return Movie
     */
    public function addVideo(\MoviesBundle\Entity\Video $videos)
    {
        $this->videos[] = $videos;

        return $this;
    }

    /**
     * Remove videos
     *
     * @param \MoviesBundle\Entity\Video $videos
     */
    public function removeVideo(\MoviesBundle\Entity\Video $videos)
    {
        $this->videos->removeElement($videos);
    }

    /**
     * Get videos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * Set director
     *
     * @param \MoviesBundle\Entity\Person $director
     * @return Movie
     */
    public function setDirector(\MoviesBundle\Entity\Person $director = null)
    {
        $this->director = $director;

        return $this;
    }

    /**
     * Get director
     *
     * @return \MoviesBundle\Entity\Person 
     */
    public function getDirector()
    {
        return $this->director;
    }

    /**
     * Add cast
     *
     * @param \MoviesBundle\Entity\MovieCast $cast
     * @return Movie
     */
    public function addCast(\MoviesBundle\Entity\MovieCast $cast)
    {
        $this->cast[] = $cast;

        return $this;
    }

    /**
     * Remove cast
     *
     * @param \MoviesBundle\Entity\MovieCast $cast
     */
    public function removeCast(\MoviesBundle\Entity\MovieCast $cast)
    {
        $this->cast->removeElement($cast);
    }

    /**
     * Get cast
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCast()
    {
        return $this->cast;
    }
}
