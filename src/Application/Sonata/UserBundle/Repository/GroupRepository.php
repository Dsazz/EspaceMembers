<?php
/**
 * This file is part of the EspaceMembers project.
 *
 * (c) Stanislav Stepanenko <dsazztazz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Repository;
use Doctrine\ORM\EntityRepository;

/**
 * GroupRepository
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
class GroupRepository extends EntityRepository
{
    public function findAllNames()
    {
        return $qb = $this->createQueryBuilder('g')
            ->select('partial g.{id, name}')
            ->orderBy('g.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
