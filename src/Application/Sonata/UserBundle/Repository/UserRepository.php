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
use Doctrine\Common\Cache\ApcCache;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use EspaceMembers\MainBundle\Entity\Event;
use EspaceMembers\MainBundle\Entity\Teaching;
use Symfony\Component\Security\Core\User\UserInterface;

use PDO;

/**
 * UserRepository
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
class UserRepository extends EntityRepository
{
    public function findTeachersAndStudents()
    {
        return $qb = $this->createQueryBuilder('u')
            ->select(
                'partial u.{
                    id, firstname, lastname, address, phone, email, biography
                }'
            )
            ->addSelect(
                'partial avatar.{
                    id, providerName, providerStatus, providerReference,
                    width, height, contentType, context
                }'
            )
            ->innerJoin('u.avatar', 'avatar')
            ->where('u.roles NOT LIKE :superadmin')
            ->orderBy('u.lastname', 'ASC')
            ->setParameter('superadmin', '%ROLE_SUPER_ADMIN%')
            ->getQuery()
            ->getResult();
    }

    public function findTeachers()
    {
        return $qb = $this->createQueryBuilder('u')
            ->select(
                'partial u.{
                    id, firstname, lastname, address, phone, email, biography
                }'
            )
            ->addSelect(
                'partial avatar.{
                    id, providerName, providerStatus, providerReference,
                    width, height, contentType, context
                }'
            )
            ->innerJoin('u.avatar', 'avatar')
            ->where('u.is_teacher = 1')
            ->orderBy('u.lastname', 'ASC')
            ->getQuery()
            ->useResultCache(true, 3600)
            ->getResult();
    }

    public function findTeacherByEvent($userId, $eventId)
    {
        return $qb = $this->createQueryBuilder('u')
            ->select(
                'partial u.{
                    id, firstname, lastname, address, phone, email, is_teacher, biography
                }'
            )
            ->addSelect(
                'partial tch.{
                    id, title, serial, lesson, dayNumber, dayTime, date
                }'
            )
            ->addSelect('partial lsn.{id, contentType, playtime}')
            ->addSelect(
                'partial avatar.{
                    id, providerName, providerStatus, providerReference,
                    width, height, contentType, context
                }'
            )
            ->innerJoin('u.teachings', 'tch', 'WITH', 'tch.is_show = 1 AND tch.event = :event_id')
            ->innerJoin('tch.lesson', 'lsn')
            ->innerJoin('u.avatar', 'avatar')
            ->where('u.is_teacher = 1 AND u.id = :user_id')
            ->setParameter("event_id", $eventId)
            ->setParameter("user_id", $userId)
            ->orderBy('u.lastname', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findTeacherAndStudentsByGroup($groupName)
    {
        return $qb = $this->createQueryBuilder('u')
            ->select(
                'partial u.{
                    id, firstname, lastname, address, phone, email, biography
                }'
            )
            ->addSelect(
                'partial avatar.{
                    id, providerName, providerStatus, providerReference,
                    width, height, contentType, context
                }'
            )
            ->innerJoin('u.avatar', 'avatar')
            ->innerJoin('u.groups', 'g', 'WITH', 'g.name = :group_name')
            ->where('u.roles NOT LIKE :superadmin')
            ->setParameter("group_name", $groupName)
            ->setParameter('superadmin', '%ROLE_SUPER_ADMIN%')
            ->getQuery()
            ->useResultCache(true, 3600)
            ->getArrayResult();
    }

    public function getBookmarksData(UserInterface $user)
    {
        $sql = "
            SELECT tu.user_id, tu.teaching_id, t.event_id
            FROM users_bookmarks as bu
            JOIN users_teachings as tu ON tu.teaching_id = bu.teaching_id
            JOIN teaching as t ON t.id = tu.teaching_id
            WHERE bu.user_id = :user_id
        ";

        $stmt = $this->_em->getConnection()->prepare($sql);
        $stmt->bindValue('user_id', $user->getId());
        $stmt->execute();

        $bookmarkData = [];
        foreach ($stmt->fetchAll() as $data) {
            $bookmarkData['user_id'][] = $data['user_id'];
            $bookmarkData['teaching_id'][] = $data['teaching_id'];
            $bookmarkData['event_id'][] = $data['event_id'];
        }

        if (empty($bookmarkData)) {
            $bookmarkData['user_id'][] = 0;
            $bookmarkData['teaching_id'][] = 0;
            $bookmarkData['event_id'][] = 0;
        }

        return $bookmarkData;
    }

    public function getBookmarksId(UserInterface $user)
    {
        $sql = "
            SELECT bu.teaching_id FROM users_bookmarks as bu
            WHERE bu.user_id = :user_id
        ";

        $stmt = $this->_em->getConnection()->prepare($sql);
        $stmt->bindValue('user_id', $user->getId());
        $stmt->execute();

        $bookmarksId = [];
        foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $data) {
            $bookmarksId[] = (int)$data;
        }

        return $bookmarksId;
    }
}
