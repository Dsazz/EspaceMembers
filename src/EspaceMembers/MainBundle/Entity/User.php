<?php

namespace EspaceMembers\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseUser as BaseUser;

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
     * @var string
     */
    protected $first_name;

    /**
     * @var string
     */
    protected $last_name;

    /**
     * @var \DateTime
     */
    protected $birthday;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var SexType
     */
    protected $sex;

    /**
     * @var \EspaceMembers\MainBundle\Entity\Media
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
    protected $phone;

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
     * Set first_name
     *
     * @param  string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get first_name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set last_name
     *
     * @param  string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get last_name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set birthday
     *
     * @param  \DateTime $birthday
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set description
     *
     * @param  string $description
     * @return User
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
     * Set sex
     *
     * @param  SexType $sex
     * @return User
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return SexType
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set avatar
     *
     * @param  \EspaceMembers\MainBundle\Entity\Media $avatar
     * @return User
     */
    public function setAvatar(\EspaceMembers\MainBundle\Entity\Media $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \EspaceMembers\MainBundle\Entity\Media
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
     * @param  \EspaceMembers\MainBundle\Entity\Group $groups
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
     * @param \EspaceMembers\MainBundle\Entity\Group $groups
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
    public function getIsTeacher()
    {
        return $this->is_teacher;
    }

    /**
     * Set phone
     *
     * @param  string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
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
     * @param  \EspaceMembers\MainBundle\Entity\Teaching $bookmarks
     * @return User
     */
    public function addBookmark(\EspaceMembers\MainBundle\Entity\Teaching $bookmark)
    {
        if (false === $this->getBookmarks()->contains($bookmark)) {
            $this->bookmarks[] = $bookmark;
            $bookmark->addBookmarkOwner($this);
        }

        return $this;
    }

    /**
     * Remove bookmarks
     *
     * @param \EspaceMembers\MainBundle\Entity\Teaching $bookmarks
     */
    public function removeBookmark(\EspaceMembers\MainBundle\Entity\Teaching $bookmarks)
    {
        $this->bookmarks->removeElement($bookmarks);
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
