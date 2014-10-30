<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

//$loader->add('GetId3',"__DIR__.'/../vendor/phansys/getid3/GetId3'");
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
