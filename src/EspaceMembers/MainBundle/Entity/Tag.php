<?php

namespace EspaceMembers\MainBundle\Entity;

class Tag
{
    private $id;
    private $name;
    private $teachings;
    private $events;

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return Tag
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
     * Constructor
     */
    public function __construct()
    {
        $this->teachings = new \Doctrine\Common\Collections\ArrayCollection();
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add teachings
     *
     * @param  \EspaceMembers\MainBundle\Entity\Teaching $teachings
     * @return Tag
     */
    public function addTeaching(\EspaceMembers\MainBundle\Entity\Teaching $teachings)
    {
        $this->teachings[] = $teachings;

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
     * Add events
     *
     * @param  \EspaceMembers\MainBundle\Entity\Event $events
     * @return Tag
     */
    public function addEvent(\EspaceMembers\MainBundle\Entity\Event $events)
    {
        $this->events[] = $events;

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

    public function __toString()
    {
        return $this->getName();
    }
}
