<?php

namespace EspaceMembers\MainBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Nelmio\Alice\Fixtures;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadFixtureData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $om)
    {
        //$mediaTypes = array('avatar', 'mp3', 'video', 'flayer', 'cover');
        //foreach ($mediaTypes as $mediaType) { $this->generateBinaryContext($mediaType); }

        Fixtures::load(__DIR__.'/EspaceMembers.yml', $om, array('providers' => array($this)));
    }


    protected $path = "/../../../../../web/uploads/fixtures/";

    /**
     * {@inheritdoc}
     */
    public function generateBinaryContext($mediaType)
    {
        $finder = new Finder();
        $binaryContents = array();

        foreach ($finder->files()->in(__DIR__.$this->path.$mediaType) as $key => $file) {
            $binaryContents[] = $file->getRealPath();
        }

        return $binaryContents[array_rand($binaryContents)];
    }


    //
    //Циклическая загрузка файлов с использованием
    //наращиваемой переменной $counter предназначеной для обеспецения
    //последовательного перехода по загружаемым файлам
    public function randBinaryContent($finish, $mediaType, $extension)
    {
        static $COUNTER = 0;
        $flaq = false;

        if($COUNTER >= $finish) {
            $COUNTER++;
            $flaq = true;
        } else $COUNTER++;

        if($flaq) $COUNTER = 1;

        $fileName = $COUNTER.$extension;


        return $binaryContent = __DIR__."/../../../../../web/uploads/fixtures/".$mediaType."/".$fileName;
    }
    //For random sex of user
    public function randTimeDay()
    {
        $time = array(
            'matin', 'midi', 'soir', 'minuit'
        );

        return $time[array_rand($time)];
    }

    //For random sex of user
    public function randSex()
    {
        $sex = array(
            'MALE', 'FEMALE'
        );

        return $sex[array_rand($sex)];
    }

    //For random role of user
    public function randRole()
    {
        $role = array(
            'ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN'
        );

        return $role[array_rand($role)];
    }
}
