<?php

namespace EspaceMembers\MainBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Cache\ApcCache;

class CategoryRepository extends EntityRepository
{
    public function getTitles()
    {
        $cacheDriver = new ApcCache();

        if ($cacheDriver->contains('_categories')) {
            return $cacheDriver->fetch('_categories');
        }

        $qb = $this->createQueryBuilder('c')
            ->select('partial c.{id, title}')
            ->orderBy('c.title', 'ASC');

        $categories = $qb->getQuery()->getResult();

        $cacheDriver->save('_categories', $categories, 60);

        return $categories;
    }
}
