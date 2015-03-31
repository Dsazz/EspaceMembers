<?php

namespace EspaceMembers\MainBundle\Entity;

class Direction
{
    private $id;
    private $name;
    private $teachings;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->teachings = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param  string $name
     * @return Direction
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
     * Add teachings
     *
     * @param  \EspaceMembers\MainBundle\Entity\Teaching $teachings
     * @return Direction
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

    public function __toString()
    {
        return $this->getName();
    }
}
