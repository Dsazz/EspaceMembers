<?php

namespace EspaceMembers\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

class Teaching 
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
     * @var integer
     */
    private $serial;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var integer
     */
    private $dayNumber;

    /**
     * @var string
     */
    private $dayTime;

    /**
     * @var string
     */
    private $resume;

    /**
     * @var string
     */
    private $technical_comment;

    /**
     * @var boolean
     */
    private $is_show;

    /**
     * @var \EspaceMembers\MainBundle\Entity\Media
     */
    private $lesson;

    /**
     * @var \EspaceMembers\MainBundle\Entity\Event
     */
    private $event;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tags;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $voies;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->voies = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param string $title
     * @return Teaching
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
     * Set serial
     *
     * @param integer $serial
     * @return Teaching
     */
    public function setSerial($serial)
    {
        $this->serial = $serial;

        return $this;
    }

    /**
     * Get serial
     *
     * @return integer 
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Teaching
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set dayNumber
     *
     * @param integer $dayNumber
     * @return Teaching
     */
    public function setDayNumber($dayNumber)
    {
        $this->dayNumber = $dayNumber;

        return $this;
    }

    /**
     * Get dayNumber
     *
     * @return integer 
     */
    public function getDayNumber()
    {
        return $this->dayNumber;
    }

    /**
     * Set dayTime
     *
     * @param string $dayTime
     * @return Teaching
     */
    public function setDayTime($dayTime)
    {
        $this->dayTime = $dayTime;

        return $this;
    }

    /**
     * Get dayTime
     *
     * @return string 
     */
    public function getDayTime()
    {
        return $this->dayTime;
    }

    /**
     * Set resume
     *
     * @param string $resume
     * @return Teaching
     */
    public function setResume($resume)
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * Get resume
     *
     * @return string 
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * Set technical_comment
     *
     * @param string $technicalComment
     * @return Teaching
     */
    public function setTechnicalComment($technicalComment)
    {
        $this->technical_comment = $technicalComment;

        return $this;
    }

    /**
     * Get technical_comment
     *
     * @return string 
     */
    public function getTechnicalComment()
    {
        return $this->technical_comment;
    }

    /**
     * Set is_show
     *
     * @param boolean $isShow
     * @return Teaching
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
     * Set lesson
     *
     * @param \EspaceMembers\MainBundle\Entity\Media $lesson
     * @return Teaching
     */
    public function setLesson(\EspaceMembers\MainBundle\Entity\Media $lesson = null)
    {
        $this->lesson = $lesson;

        return $this;
    }

    /**
     * Get lesson
     *
     * @return \EspaceMembers\MainBundle\Entity\Media 
     */
    public function getLesson()
    {
        return $this->lesson;
    }

    /**
     * Set event
     *
     * @param \EspaceMembers\MainBundle\Entity\Event $event
     * @return Teaching
     */
    public function setEvent(\EspaceMembers\MainBundle\Entity\Event $event = null)
    {
        $this->event = $event;
        //$event->addTeaching($this);

        return $this;
    }

    /**
     * Get event
     *
     * @return \EspaceMembers\MainBundle\Entity\Event 
     */
    public function getEvent()
    {
        return $this->event;
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
     * @param \EspaceMembers\MainBundle\Entity\Tag $tags
     * @return Teaching
     */
    public function addTag(\EspaceMembers\MainBundle\Entity\Tag $tag)
    {
        if ( false === $this->getTags()->contains($tag) ) {
            $this->tags[] = $tag;
            $tag->addTeaching($this);
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

    public function setVoies(ArrayCollection $voies)
    {
        foreach ($voies as $voie) {
            $this->addVoie($voie);
        }
    }

    /**
     * Add voies
     *
     * @param \EspaceMembers\MainBundle\Entity\Voie $voies
     * @return Teaching
     */
    public function addVoie(\EspaceMembers\MainBundle\Entity\Voie $voie)
    {
        if ( false === $this->getVoies()->contains($voie) ) {
            $this->voies[] = $voie;
            $voie->addTeaching($this);
        }

        return $this;
    }

    /**
     * Remove voies
     *
     * @param \EspaceMembers\MainBundle\Entity\Voie $voies
     */
    public function removeVoie(\EspaceMembers\MainBundle\Entity\Voie $voies)
    {
        $this->voies->removeElement($voies);
    }

    /**
     * Get voies
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVoies()
    {
        return $this->voies;
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
     * @param \EspaceMembers\MainBundle\Entity\User $users
     * @return Teaching
     */
    public function addUser(\EspaceMembers\MainBundle\Entity\User $user)
    {
        if ( false === $this->getUsers()->contains($user) ) {
            $this->users[] = $user;
            //$user->addTeaching($this);
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

    public function __toString()
    {
        return $this->getTitle() ?: '';
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $bookmarkOwners;


    /**
     * Add bookmarkOwners
     *
     * @param \EspaceMembers\MainBundle\Entity\User $bookmarkOwners
     * @return Teaching
     */
    public function addBookmarkOwner(\EspaceMembers\MainBundle\Entity\User $bookmarkOwners)
    {
        $this->bookmarkOwners[] = $bookmarkOwners;

        return $this;
    }

    /**
     * Remove bookmarkOwners
     *
     * @param \EspaceMembers\MainBundle\Entity\User $bookmarkOwners
     */
    public function removeBookmarkOwner(\EspaceMembers\MainBundle\Entity\User $bookmarkOwners)
    {
        $this->bookmarkOwners->removeElement($bookmarkOwners);
    }

    /**
     * Get bookmarkOwners
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBookmarkOwners()
    {
        return $this->bookmarkOwners;
    }
}
