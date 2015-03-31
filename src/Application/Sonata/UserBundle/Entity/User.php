<?php

/**
 * This file is part of the EspaceMembers project.
 *
 * (c) Stanislav Stepanenko dsazztazz@gmail.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 */
class User extends BaseUser
{
    protected static $ROLE_DEFAULT     = 'ROLE_USER';
    protected static $ROLE_ADMIN       = 'ROLE_ADMIN';
    protected static $ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     */
    protected $avatar;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $events;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $teachings;

    /**
     * @var boolean
     */
    protected $is_teacher;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $groups;

    /**
     *@var array
     */
    protected $roles;

    /**
     * @var string
     */
    private $address;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $bookmarks;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->events    = new \Doctrine\Common\Collections\ArrayCollection();
        $this->teachings = new \Doctrine\Common\Collections\ArrayCollection();
        $this->groups    = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles     = array();
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
     * Set avatar
     *
     * @param  \Sonata\MediaBundle\Model\MediaInterface $avatar
     * @return User
     */
    public function setAvatar(\Sonata\MediaBundle\Model\MediaInterface $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return  \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Add events
     *
     * @param  \EspaceMembers\MainBundle\Entity\Event $events
     * @return User
     */
    public function addEvent(\EspaceMembers\MainBundle\Entity\Event $event)
    {
        if (false === $this->getEvents()->contains($event)) {
            $this->events[] = $event;
            $event->addUser($this);
        }

        return $this;
    }

    /**
     * Remove events
     *
     * @param \EspaceMembers\MainBundle\Entity\Event $events
     */
    public function removeEvent(\EspaceMembers\MainBundle\Entity\Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvents()
    {
        return $this->events;
    }

    public function setTeachings(ArrayCollection $teachings)
    {
        foreach ($teachings as $teaching) {
            $this->addTeaching($teaching);
        }
    }

    /**
     * Add teachings
     *
     * @param  \EspaceMembers\MainBundle\Entity\Teaching $teachings
     * @return User
     */
    public function addTeaching(\EspaceMembers\MainBundle\Entity\Teaching $teaching)
    {
        if (false === $this->getTeachings()->contains($teaching)) {
            $this->teachings[] = $teaching;
            $teaching->addUser($this);
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
     * Add groups
     *
     * @param  \FOS\UserBundle\Model\GroupInterface $groups
     * @return User
     */
    public function addGroup(\FOS\UserBundle\Model\GroupInterface $groups)
    {
        $this->groups[] = $groups;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param  \FOS\UserBundle\Model\GroupInterface $groups
     */
    public function removeGroup(\FOS\UserBundle\Model\GroupInterface $groups)
    {
        $this->groups->removeElement($groups);
    }

    public function addRole($role)
    {

        $role = strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }

        if (is_array($this->roles)) {
            if (!in_array($role, $this->roles, true)) {
                $this->roles[] = $role;
            }
        } else {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setDefaultRole()
    {
        $this->addRole(static::$ROLE_DEFAULT);
    }

    public function setEmail($email)
    {
        $email = is_null($email) ? '' : $email;
        parent::setEmail($email);
        $this->setUsername($email);
    }

    /**
     * Set is_teacher
     *
     * @param  boolean $isTeacher
     * @return User
     */
    public function setIsTeacher($isTeacher)
    {
        $this->is_teacher = $isTeacher;

        return $this;
    }

    /**
     * Get is_teacher
     *
     * @return boolean
     */
    public function isTeacher()
    {
        return $this->is_teacher;
    }

    /**
     * Set address
     *
     * @param  string $address
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Add bookmarks
     *
     * @param  \EspaceMembers\MainBundle\Entity\Teaching $bookmark
     * @return User
     */
    public function addBookmark(\EspaceMembers\MainBundle\Entity\Teaching $bookmark)
    {
        if (false === $this->getBookmarks()->contains($bookmark)) {
            $this->bookmarks[] = $bookmark;
        }

        return $this;
    }

    /**
     * Remove bookmarks
     *
     * @param \EspaceMembers\MainBundle\Entity\Teaching $bookmark
     */
    public function removeBookmark(\EspaceMembers\MainBundle\Entity\Teaching $bookmark)
    {
        $this->bookmarks->removeElement($bookmark);
    }

    /**
     * Get bookmarks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBookmarks()
    {
        return $this->bookmarks;
    }

}
