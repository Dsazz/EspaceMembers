<?php

namespace EspaceMembers\MainBundle\Entity;

use Sonata\MediaBundle\Entity\BaseMedia as BaseMedia;

class Media extends BaseMedia
{
    protected $id;
    protected $playtime;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set playtime
     *
     * @param  string $playtime
     * @return Media
     */
    public function setPlaytime($playtime)
    {
        $this->playtime = $playtime;

        return $this;
    }

    /**
     * Get playtime
     *
     * @return string
     */
    public function getPlaytime()
    {
        return $this->playtime;
    }
}
