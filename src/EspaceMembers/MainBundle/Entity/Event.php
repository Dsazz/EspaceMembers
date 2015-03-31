<?php

namespace EspaceMembers\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

class Event
{
    /*
     *@const MAX_PER_PAGE the maximum projects on the page
     */
    const MAX_PER_PAGE = 10;

    private $id;
    private $title;
    private $startDate;
    private $completionDate;
    private $year;
    private $description;
    private $is_show;
    private $frontImage;
    private $flayer;
    private $teachings;
    private $category;
    private $users;
    private $tags;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->teachings = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set title
     *
     * @param  string $title
     * @return Event
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
     * Set startDate
     *
     * @param  \DateTime $startDate
     * @return Event
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set completionDate
     *
     * @param  \DateTime $completionDate
     * @return Event
     */
    public function setCompletionDate($completionDate)
    {
        $this->completionDate = $completionDate;

        return $this;
    }

    /**
     * Get completionDate
     *
     * @return \DateTime
     */
    public function getCompletionDate()
    {
        return $this->completionDate;
    }

    /**
     * Set year
     *
     * @param  smallint $year
     * @return Event
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set description
     *
     * @param  string $description
     * @return Event
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
     * Set is_show
     *
     * @param  boolean $isShow
     * @return Event
     */
    public function setIsShow($isShow)
    {
        $this->is_show = $isShow;

        return $this;
    }

    /**
     * Get is_show
     *
     * @return boolean
     */
    public function getIsShow()
    {
        return $this->is_show;
    }

    /**
     * Set frontImage
     *
     * @param  \Sonata\MediaBundle\Model\MediaInterface $frontImage
     * @return Event
     */
    public function setFrontImage(\Sonata\MediaBundle\Model\MediaInterface $frontImage = null)
    {
        $this->frontImage = $frontImage;

        return $this;
    }

    /**
     * Get frontImage
     *
     * @return \Sonata\MediaBundle\Model\MediaInterface
     */
    public function getFrontImage()
    {
        return $this->frontImage;
    }

    /**
     * Set flayer
     *
     * @param  \Sonata\MediaBundle\Model\MediaInterface $flayer
     * @return Event
     */
    public function setFlayer(\Sonata\MediaBundle\Model\MediaInterface $flayer = null)
    {
        $this->flayer = $flayer;

        return $this;
    }

    /**
     * Get flayer
     *
     * @return \Sonata\MediaBundle\Model\MediaInterface
     */
    public function getFlayer()
    {
        return $this->flayer;
    }

    /**
     * Add teachings
     *
     * @param  \EspaceMembers\MainBundle\Entity\Teaching $teachings
     * @return Event
     */
    public function addTeaching(\EspaceMembers\MainBundle\Entity\Teaching $teaching)
    {
        if (false === $this->getTeachings()->contains($teaching)) {
            $this->teachings[] = $teaching;
        }

        return $this;
    }

    /**
     * Remove teachings
     *
     * @param \EspaceMembers\MainBundle\Entity\Teaching $teachings
     */
    public function removeTeaching(\EspaceMembers\MainBundle\Entity\Teaching $teachings)
    {
        $this->teachings->removeElement($teachings);
    }

    /**
     * Get teachings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeachings()
    {
        return $this->teachings;
    }

    /**
     * Set category
     *
     * @param  \EspaceMembers\MainBundle\Entity\Category $category
     * @return Event
     */
    public function setCategory(\EspaceMembers\MainBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \EspaceMembers\MainBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function setUsers(ArrayCollection $users)
    {
        foreach ($users as $user) {
            $this->addUser($user);
        }
    }

    /**
     * Add users
     *
     * @param  \Symfony\Component\Security\Core\User\UserInterface $users
     * @return Event
     */
    public function addUser(\Symfony\Component\Security\Core\User\UserInterface $user)
    {
        if ( false === $this->getUsers()->contains($user) ) {
            $this->users[] = $user;
            $user->addEvent($this);
        }

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     */
    public function removeUser(\Symfony\Component\Security\Core\User\UserInterface $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function setTags(ArrayCollection $tags)
    {
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }
    }

    /**
     * Add tags
     *
     * @param  \EspaceMembers\MainBundle\Entity\Tag $tags
     * @return Event
     */
    public function addTag(\EspaceMembers\MainBundle\Entity\Tag $tag)
    {
        if (false === $this->getTags()->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addEvent($this);
        }

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \EspaceMembers\MainBundle\Entity\Tag $tags
     */
    public function removeTag(\EspaceMembers\MainBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}
