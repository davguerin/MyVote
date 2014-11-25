<?php

namespace DG\VoteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vote
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="DG\VoteBundle\Entity\VoteRepository")
 */
class Vote
{
    /**
     * @var integer
     *
     * @ORM\Column(name="vote", type="smallint")
     */
    private $vote;

    /**
     * @var text
     * 
     * @ORM\Column(name="description", type="text")
     */
    private $description;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="DG\VoteBundle\Entity\Sheet")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sheet;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="DG\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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
     * Set user
     *
     * @param \DG\UserBundle\Entity\User $user
     * @return Vote
     */
    public function setUser(\DG\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \DG\UserBundle\Entity\User 
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

    /**
     * Set description
     *
     * @param string $description
     * @return Vote
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
}
