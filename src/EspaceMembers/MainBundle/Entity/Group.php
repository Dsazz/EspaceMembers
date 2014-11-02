<?php

namespace EspaceMembers\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Entity\Group as BaseGroup;

/**
 * Represents a Base Group Entity
 */
class Group extends BaseGroup
{
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $events;

    public function __toString()
    {
        return $this->getName() ?: '';
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
     * Constructor
     */
    public function __construct()
    {
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
        $this->addRole('ROLE_USER');
    }

    /**
     * Add events
     *
     * @param  \EspaceMembers\MainBundle\Entity\Event $events
     * @return Group
     */
    public function addEvent(\EspaceMembers\MainBundle\Entity\Event $event)
    {
        $this->events[] = $event;
        //$event->addGroup($this);
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

    public function addRole($role)
    {
        if (is_array($this->roles)) {
            if (!in_array($role, $this->roles, true)) {
                $this->roles[] = $role;
            }
        } else {
            $this->roles[] = $role;
        }

        return $this;
    }

}
