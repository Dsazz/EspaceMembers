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

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityRepository;

/**
 * TeachingRepository
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
class TeachingRepository extends EntityRepository
{
    public function findPartialOneById($teaching_id)
    {
        return $qb = $this->createQueryBuilder('tch')
            ->select('tch')
            ->addSelect('partial lsn.{id, contentType, context, providerName, providerStatus, providerReference}')
            ->addSelect('partial u.{id, lastname, firstname}')
            ->addSelect('v')
            ->addSelect('tags')
            ->innerJoin('tch.lesson', 'lsn')
            ->innerJoin('tch.users', 'u')
            ->innerJoin('tch.directions', 'v')
            ->innerJoin('tch.tags', 'tags')
            ->where('tch.is_show = 1 AND tch.id = :teaching_id')
            ->setParameter("teaching_id", $teaching_id)
            ->getQuery()
            ->getOneOrNullResult(Query::HYDRATE_ARRAY);
    }
}
