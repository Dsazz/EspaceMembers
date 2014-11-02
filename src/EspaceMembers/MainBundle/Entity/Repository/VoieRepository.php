<?php

namespace EspaceMembers\MainBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Cache\ApcCache;

class VoieRepository extends EntityRepository
{
    public function getTitles()
    {
        $cacheDriver = new ApcCache();

        if ($cacheDriver->contains('_voies')) {
            return $cacheDriver->fetch('_voies');
        }

        $qb = $this->createQueryBuilder('v')
            ->select('partial v.{id, title}')
            ->orderBy('v.title', 'ASC');

        $voies = $qb->getQuery()->getResult();

        $cacheDriver->save('_voies', $voies, 60);

        return $voies;
    }
}
