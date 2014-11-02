<?php

namespace EspaceMembers\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

class Event
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $completionDate;

    /**
     * @var string
     */
    private $year;

    /**
     * @var string
     */
    private $description;

    /**
     * @var boolean
     */
    private $is_show;

    /**
     * @var \EspaceMembers\MainBundle\Entity\Media
     */
    private $frontImage;

    /**
     * @var \EspaceMembers\MainBundle\Entity\Media
     */
    private $flayer;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $teachings;

    /**
     * @var \EspaceMembers\MainBundle\Entity\Category
     */
    private $category;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $users;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $groups;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tags;

    /**
     * @var \EspaceMembers\MainBundle\Entity\Chronology
     */
    private $chronology;
    /**
     * Constructor
     */

    public function __construct()
    {
        $this->teachings = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param  string $year
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
     * @param  \EspaceMembers\MainBundle\Entity\Media $frontImage
     * @return Event
     */
    public function setFrontImage(\EspaceMembers\MainBundle\Entity\Media $frontImage = null)
    {
        $this->frontImage = $frontImage;

        return $this;
    }

    /**
     * Get frontImage
     *
     * @return \EspaceMembers\MainBundle\Entity\Media
     */
    public function getFrontImage()
    {
        return $this->frontImage;
    }

    /**
     * Set flayer
     *
     * @param  \EspaceMembers\MainBundle\Entity\Media $flayer
     * @return Event
     */
    public function setFlayer(\EspaceMembers\MainBundle\Entity\Media $flayer = null)
    {
        $this->flayer = $flayer;

        return $this;
    }

    /**
     * Get flayer
     *
     * @return \EspaceMembers\MainBundle\Entity\Media
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
        $this->teachings[] = $teaching;
        $teaching->setEvent($this);

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
     * @param  \EspaceMembers\MainBundle\Entity\User $users
     * @return Event
     */
    public function addUser(\EspaceMembers\MainBundle\Entity\User $user)
    {
        if ( false === $this->getUsers()->contains($user) ) {
            $this->users[] = $user;
        }

        return $this;
    }

    /**
     * Remove users
     *
     * @param \EspaceMembers\MainBundle\Entity\User $users
     */
    public function removeUser(\EspaceMembers\MainBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
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

    public function setGroups(ArrayCollection $groups)
    {
        foreach ($groups as $group) {
            $this->addGroup($group);
        }
    }

    /**
     * Add groups
     *
     * @param  \EspaceMembers\MainBundle\Entity\Group $groups
     * @return Event
     */
    public function addGroup(\EspaceMembers\MainBundle\Entity\Group $group)
    {
        if ( false === $this->getGroups()->contains($group) ) {
            $this->groups[] = $group;
            $group->addEvent($this);
        }

        return $this;
    }

    /**
     * Remove groups
     *
     * @param \EspaceMembers\MainBundle\Entity\Group $groups
     */
    public function removeGroup(\EspaceMembers\MainBundle\Entity\Group $groups)
    {
        $this->groups->removeElement($groups);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
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
        if ( false === $this->getTags()->contains($tag) ) {
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
    /**
     * @ORM\PrePersist
     */
    public function setDefaultGroup()
    {
        // Add your code here
    }
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * Set chronology
     *
     * @param  \EspaceMembers\MainBundle\Entity\Chronology $chronology
     * @return Event
     */
    public function setChronology(\EspaceMembers\MainBundle\Entity\Chronology $chronology = null)
    {
        $this->chronology = $chronology;
        $chronology->addEvent($this);

        return $this;
    }

    /**
     * Get chronology
     *
     * @return \EspaceMembers\MainBundle\Entity\Chronology
     */
    public function getChronology()
    {
        return $this->chronology;
    }
}
