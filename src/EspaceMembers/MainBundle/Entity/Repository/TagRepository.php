<?php

namespace EspaceMembers\MainBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Cache\ApcCache;

class TagRepository extends EntityRepository
{
    public function getTitles()
    {
        $cacheDriver = new ApcCache();

        if ($cacheDriver->contains('_tags')) {
            return $cacheDriver->fetch('_tags');
        }

        $qb = $this->createQueryBuilder('t')
            ->select('partial t.{id, title}')
            ->orderBy('t.title', 'ASC');

        $tags = $qb->getQuery()->getResult();

        $cacheDriver->save('_tags', $tags, 60);

        return $tags;
    }
}
