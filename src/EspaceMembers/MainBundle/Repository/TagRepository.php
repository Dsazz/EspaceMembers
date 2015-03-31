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
 * TagRepository
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
class TagRepository extends EntityRepository
{
    public function getNames()
    {
        return $qb = $this->createQueryBuilder('t')
            ->select('partial t.{id, name}')
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->useResultCache(true, 3600)
            ->getResult();
    }
}
