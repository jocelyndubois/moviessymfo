<?php

namespace MoviesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MovieCast
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MoviesBundle\Entity\MovieCastRepository")
 */
class MovieCast
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
     * @ORM\ManyToOne(targetEntity="Movie", inversedBy="cast", cascade={"remove"})
     * @ORM\JoinColumn(name="movie_id", referencedColumnName="id")
     */
    protected $movie;

    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="movie", cascade={"remove"})
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    protected $person;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255)
     */
    private $role;


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
     * Set role
     *
     * @param string $role
     * @return MovieCast
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set movie
     *
     * @param \MoviesBundle\Entity\Movie $movie
     * @return MovieCast
     */
    public function setMovie(\MoviesBundle\Entity\Movie $movie = null)
    {
        $this->movie = $movie;

        return $this;
    }

    /**
     * Get movie
     *
     * @return \MoviesBundle\Entity\Movie 
     */
    public function getMovie()
    {
        return $this->movie;
    }

    /**
     * Set person
     *
     * @param \MoviesBundle\Entity\Person $person
     * @return MovieCast
     */
    public function setPerson(\MoviesBundle\Entity\Person $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \MoviesBundle\Entity\Person 
     */
    public function getPerson()
    {
        return $this->person;
    }
}
