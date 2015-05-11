<?php

namespace MoviesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MovieUser
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MoviesBundle\Entity\MovieUserRepository")
 */
class MovieUser
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
     * @ORM\ManyToOne(targetEntity="Movie", inversedBy="users", cascade={"remove"})
     * @ORM\JoinColumn(name="movie_id", referencedColumnName="id")
     */
    protected $movie;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="movies", cascade={"remove"})
     * @ORM\JoinColumn(name="users_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(name="localName", type="string", length=255)
     */
    private $localName;


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
     * Set localName
     *
     * @param string $localName
     *
     * @return MovieUser
     */
    public function setLocalName($localName)
    {
        $this->localName = $localName;

        return $this;
    }

    /**
     * Get localName
     *
     * @return string
     */
    public function getLocalName()
    {
        return $this->localName;
    }

    /**
     * Set movie
     *
     * @param \MoviesBundle\Entity\Movie $movie
     *
     * @return MovieUser
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
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return MovieUser
     */
    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
