<?php
namespace EspaceMembers\MainBundle\DBAL\Types;

use Fresh\Bundle\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class SexType extends AbstractEnumType
{
    const MALE   = 'MALE';
    const FEMALE = 'FEMALE';

    /**
     * @var array Readable choices
     * @static
     */
    protected static $choices = [
        self::MALE   => 'MALE',
        self::FEMALE => 'FEMALE',
    ];
}
