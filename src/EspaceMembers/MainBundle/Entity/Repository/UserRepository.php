<?php

namespace EspaceMembers\MainBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use EspaceMembers\MainBundle\Entity\Event;
use Symfony\Component\Security\Core\User\UserInterface;

use PDO;

class UserRepository extends EntityRepository
{
    public function findTeachers()
    {
        return $qb = $this->createQueryBuilder('u')
            ->select(
                'partial u.{
                    id, first_name, last_name, address, phone, email, is_teacher
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
            ->getQuery()
            ->useResultCache(true, 3600)
            ->getResult();
    }

    public function findTeachersByGroup($groupName)
    {
        return $qb = $this->createQueryBuilder('u')
            ->select(
                'partial u.{
                    id, first_name, last_name, address, phone, email, is_teacher
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
            ->where('u.is_teacher = 1')
            ->setParameter("group_name", $groupName)
            ->getQuery()
            ->useResultCache(true, 3600)
            ->getResult();
    }

    public function findTeachersWithLessons()
    {
        return $qb = $this->createQueryBuilder('u')
            ->select(
                'partial u.{
                    id, first_name, last_name, address, phone, email, is_teacher
                }'
            )
            ->addSelect(
                'partial avatar.{
                    id, providerName, providerStatus, providerReference,
                    width, height, contentType, context
                }'
            )

            ->innerJoin('u.teachings', 'tch', 'WITH', 'tch.is_show = 1')
            ->innerJoin('u.avatar', 'avatar')
            ->innerJoin('tch.event', 'ev')
            ->where('u.is_teacher = 1')
            ->orderBy('u.last_name', 'ASC')
            ->getQuery()
            ->useResultCache(true, 3600)
            ->getResult();
    }

    //TODO: Create partial select
    public function findTeachingsByEvent($userId, $eventId)
    {
        return $qb = $this->createQueryBuilder('u')
            ->select('u, t')
            ->innerJoin('u.teachings', 't', 'WITH', 't.is_show = 1')
            ->innerJoin('t.event', 'e', 'WITH', 'e.id = :eventId')
            ->where('u.is_teacher = 1')
            ->andWhere('u.id = :user_id')
            ->setParameter("eventId", $eventId)
            ->setParameter("user_id", $userId)
            ->orderBy('u.last_name', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function isBookmark($userId, $teachingId)
    {
        return (bool) $qb = $this->createQueryBuilder('u')
            ->select('u.id')
            ->innerJoin('u.teachings', 't', 'WITH', 't.id = :teaching_id')
            ->where("u.id = :user_id")
            ->setParameter("teaching_id", $teachingId)
            ->setParameter("user_id", $userId)
            ->getQuery()
            ->getOneOrNullResult();
    }



    public function getBookmarksData(UserInterface $user)
    {
        $sql = "
            SELECT tu.user_id, tu.teaching_id, t.event_id
            FROM bookmarks_users as bu
            JOIN teachings_users as tu ON tu.teaching_id = bu.teaching_id
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

        return $bookmarkData;
    }

    public function getBookmarksId(UserInterface $user)
    {
        return $qb = $this->createQueryBuilder('u')
            ->select('partial u.{id}')
            ->addSelect('partial bk.{id}')
            ->innerJoin('u.bookmarks', 'bk')
            ->where('u = :user')
            ->setParameter("user", $user)
            ->getQuery()
            ->getOneOrNullResult(Query::HYDRATE_ARRAY);
    }
}
