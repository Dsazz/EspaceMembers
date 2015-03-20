<?php

namespace EspaceMembers\MainBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Cache\ApcCache;

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

    public function findBookmarks($userId)
    {
        return $qb = $this->createQueryBuilder('u')
            ->select('partial u.{id}')
            ->addSelect('partial tu.{id, last_name, first_name}')
            ->addSelect(
                'partial ev.{
                    id, title, year, category, frontImage, startDate, completionDate
                }'
            )
            ->addSelect(
                'partial bk.{
                    id, title, serial,lesson, dayNumber, dayTime, date
                }'
            )
            ->addSelect('partial tch.{id}')
            ->addSelect('partial lsn.{id, contentType, path}')
            ->addSelect(
                'partial frntImg.{
                    id, providerName, providerStatus, providerReference,
                    width, height, contentType, context
                }'
            )
            ->addSelect(
                'partial avatar.{
                    id, providerName, providerStatus, providerReference,
                    width, height, contentType, context
                }'
            )
            ->addSelect(
                'partial tavatar.{
                    id, providerName, providerStatus, providerReference,
                    width, height, contentType, context
                }'
            )

            ->innerJoin('u.bookmarks', 'bk')
            ->innerJoin('u.avatar', 'avatar')
            ->innerJoin('bk.event', 'ev')
            ->innerJoin('ev.users', 'tu')
            ->innerJoin('tu.teachings', 'tch', 'WITH', 'tch.id = bk.id')
            ->innerJoin('tu.avatar', 'tavatar')
            ->innerJoin('tch.lesson', 'lsn')
            ->innerJoin('ev.frontImage', 'frntImg')
            ->where('u.id = :user_id')
            ->setParameter("user_id", $userId)
            ->orderBy('ev.year', 'DESC')
            ->getQuery()
            ->getSingleResult();
    }
}
