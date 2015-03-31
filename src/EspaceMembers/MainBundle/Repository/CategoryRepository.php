<?php
/**
 * This file is part of the EspaceMembers project.
 *
 * (c) Stanislav Stepanenko <dsazztazz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EspaceMembers\MainBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Cache\ApcCache;

/**
 * CategoryRepository
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
class CategoryRepository extends EntityRepository
{
    public function getNames()
    {
        return $qb = $this->createQueryBuilder('c')
            ->select('partial c.{id, name}')
            ->innerJoin('c.events', 'ev')
            ->innerJoin('ev.users', 'u', 'WITH', 'u.is_teacher = 1')
            ->innerJoin('u.teachings', 'tch', 'WITH', 'tch.is_show = 1')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->useResultCache(true, 3600)
            ->getResult();
    }
}
