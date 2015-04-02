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
 * DirectionRepository
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
class DirectionRepository extends EntityRepository
{
    public function getNames()
    {
        return $qb = $this->createQueryBuilder('v')
            ->select('partial v.{id, name}')
            ->innerJoin('v.teachings','tch', 'WITH', 'tch.is_show = 1')
            ->innerJoin('tch.event','ev')
            ->groupBy('v.name')
            ->having('COUNT(tch.id) > 0 AND COUNT(ev.id) > 0')
            ->getQuery()
            ->useResultCache(true, 3600)
            ->getResult();
    }
}
