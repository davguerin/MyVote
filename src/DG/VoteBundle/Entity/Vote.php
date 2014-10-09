<?php

namespace DG\VoteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vote
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Vote
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
     * @var integer
     *
     * @ORM\Column(name="vote", type="smallint")
     */
    private $vote;

    /**
     * @ORM\ManyToOne(targetEntity="DG\VoteBundle\Entity\Sheet")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sheet;

    /**
     * @ORM\ManyToOne(targetEntity="DG\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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
     * Set vote
     *
     * @param integer $vote
     * @return Vote
     */
    public function setVote($vote)
    {
        $this->vote = $vote;

        return $this;
    }

    /**
     * Get vote
     *
     * @return integer 
     */
    public function getVote()
    {
        return $this->vote;
    }

    /**
     * Set movie
     *
     * @param \DG\VoteBundle\Entity\Movie $movie
     * @return Vote
     */
    public function setMovie(\DG\VoteBundle\Entity\Movie $movie)
    {
        $this->movie = $movie;

        return $this;
    }

    /**
     * Get movie
     *
     * @return \DG\VoteBundle\Entity\Movie 
     */
    public function getMovie()
    {
        return $this->movie;
    }

    /**
     * Set user
     *
     * @param \DG\VoteBundle\Entity\User $user
     * @return Vote
     */
    public function setUser(\DG\VoteBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \DG\VoteBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set sheet
     *
     * @param \DG\VoteBundle\Entity\Sheet $sheet
     * @return Vote
     */
    public function setSheet(\DG\VoteBundle\Entity\Sheet $sheet)
    {
        $this->sheet = $sheet;

        return $this;
    }

    /**
     * Get sheet
     *
     * @return \DG\VoteBundle\Entity\Sheet 
     */
    public function getSheet()
    {
        return $this->sheet;
    }
}
