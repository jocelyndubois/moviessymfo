<?php

namespace UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

//    /**
//     * @ORM\ManyToMany(targetEntity="MoviesBundle\Entity\Movie", inversedBy="users")
//     * @ORM\JoinTable(name="movies_users")
//     */
//    private $movies;

    /**
     * @ORM\OneToMany(targetEntity="MoviesBundle\Entity\MovieUser", mappedBy="user", cascade={"remove", "persist"})
     */
    private $movies;


    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Add movie
     *
     * @param \MoviesBundle\Entity\MovieUser $movie
     *
     * @return User
     */
    public function addMovie(\MoviesBundle\Entity\MovieUser $movie)
    {
        $this->movies[] = $movie;

        return $this;
    }

    /**
     * Remove movie
     *
     * @param \MoviesBundle\Entity\MovieUser $movie
     */
    public function removeMovie(\MoviesBundle\Entity\MovieUser $movie)
    {
        $this->movies->removeElement($movie);
    }

    /**
     * Get movies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovies()
    {
        return $this->movies;
    }

    public function hasMovie(\MoviesBundle\Entity\Movie $movie){
        foreach ($this->getMovies() as $movieUser) {
            if($movieUser->getMovie() == $movie) {
                return true;
            }
        }

        return false;
    }
}
