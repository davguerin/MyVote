<?php

namespace DG\VoteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sheet
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Sheet
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="DG\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var string
     * 
     * @ORM\Column(name="image", type="text", nullable=true)
     */
    private $image;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="launch_date_theater_us", type="datetime", nullable=true)
     */
    private $launch_date_theater_us;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="launch_date_theater_fr", type="datetime", nullable=true)
     */
    private $launch_date_theater_fr;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="launch_date_dvd", type="datetime", nullable=true)
     */
    private $launch_date_dvd;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="launch_date_blue_ray", type="datetime", nullable=true)
     */
    private $launch_date_blue_ray;

    public $file;
    
    private $last_image_name;

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
     * Set name
     *
     * @param string $name
     * @return Sheet
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Sheet
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

    /**
     * Set image
     *
     * @param string $image
     * @return Sheet
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
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
    
    public function getAbsolutePath()
    {
        return null === $this->image ? null : $this->getUploadRootDir().'/'.$this->image;
    }

    public function getWebPath()
    {
        return null === $this->image ? null : $this->getUploadDir().'/'.$this->image;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'images';
    }
    
    public function preUpload()
    {
        if (null !== $this->file) {
            if(!empty($this->image))
                $this->last_image_name = $this->image;
            $this->image = sha1(uniqid(mt_rand(), true)).'.'.$this->file->guessExtension();
        }
    }
    
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function uploadImage()
    {
        if($this->file === null)
            return;
        
        $this->file->move($this->getUploadRootDir(), $this->image);
        
        $this->file = $this->image;
        
        if(!empty($this->last_image_name))
            unlink($this->getUploadRootDir().'/'.$this->last_image_name);
    }
    
    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath())
            unlink($file);
    }

    /**
     * Set launch_date_theater_us
     *
     * @param \DateTime $launchDateTheaterUs
     * @return Sheet
     */
    public function setLaunchDateTheaterUs($launchDateTheaterUs)
    {
        $this->launch_date_theater_us = $launchDateTheaterUs;

        return $this;
    }

    /**
     * Get launch_date_theater_us
     *
     * @return \DateTime 
     */
    public function getLaunchDateTheaterUs()
    {
        return $this->launch_date_theater_us;
    }

    /**
     * Set launch_date_theater_fr
     *
     * @param \DateTime $launchDateTheaterFr
     * @return Sheet
     */
    public function setLaunchDateTheaterFr($launchDateTheaterFr)
    {
        $this->launch_date_theater_fr = $launchDateTheaterFr;

        return $this;
    }

    /**
     * Get launch_date_theater_fr
     *
     * @return \DateTime 
     */
    public function getLaunchDateTheaterFr()
    {
        return $this->launch_date_theater_fr;
    }

    /**
     * Set launch_date_dvd
     *
     * @param \DateTime $launchDateDvd
     * @return Sheet
     */
    public function setLaunchDateDvd($launchDateDvd)
    {
        $this->launch_date_dvd = $launchDateDvd;

        return $this;
    }

    /**
     * Get launch_date_dvd
     *
     * @return \DateTime 
     */
    public function getLaunchDateDvd()
    {
        return $this->launch_date_dvd;
    }

    /**
     * Set launch_date_blue_ray
     *
     * @param \DateTime $launchDateBlueRay
     * @return Sheet
     */
    public function setLaunchDateBlueRay($launchDateBlueRay)
    {
        $this->launch_date_blue_ray = $launchDateBlueRay;

        return $this;
    }

    /**
     * Get launch_date_blue_ray
     *
     * @return \DateTime 
     */
    public function getLaunchDateBlueRay()
    {
        return $this->launch_date_blue_ray;
    }
}
