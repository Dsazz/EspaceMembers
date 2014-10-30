<?php

namespace EspaceMembers\MainBundle\Entity;

use Sonata\MediaBundle\Entity\BaseMedia as BaseMedia;
use Doctrine\ORM\Mapping as ORM;
use GetId3\GetId3Core as GetId3;

class Media extends BaseMedia {

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
     * @param string $path
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
    
    public function getPlayingTime($pathToMedia) {
        //$pathToMedia = '/path/to/my/mp3file.mp3';
        //var_dump($pathToMedia->__toString());die;
        //$absolutePath = 
        $pathToMedia =  __DIR__.'/../../../../web'.trim($pathToMedia);
        $getId3 = new GetId3();
        $audio = $getId3
            //->setOptionMD5Data(true)
            //->setOptionMD5DataSource(true)
            //->setEncoding('UTF-8')
            ->analyze($pathToMedia)
            ;
        //var_dump($audio); die;

        //if (isset($audio['error'])) {
            //throw new \RuntimeException(sprintf('Error at reading audio properties from "%s" with GetId3: %s.', $pathToMedia, $audio['error']));
        //}
        $this->setLength(isset($audio['playtime_string']) ? $audio['playtime_string'] : '');

        return $this->getLength();
    }
}
