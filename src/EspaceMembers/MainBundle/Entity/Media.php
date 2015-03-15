<?php

namespace EspaceMembers\MainBundle\Entity;

use Sonata\MediaBundle\Entity\BaseMedia as BaseMedia;
use GetId3\GetId3Core as GetId3;

class Media extends BaseMedia
{

    protected $id;

    /**
     * @var string
     */
    protected $path;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set path
     *
     * @param  string $path
     * @return Media
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    public function getPlayingTime($pathToMedia)
    {
        $pathToMedia =  __DIR__.'/../../../../web'.trim($pathToMedia);
        $getId3 = new GetId3();

        $audio = $getId3->analyze($pathToMedia);
        $this->setLength(isset($audio['playtime_string']) ? $audio['playtime_string'] : '');

        return $this->getLength();
    }
}
