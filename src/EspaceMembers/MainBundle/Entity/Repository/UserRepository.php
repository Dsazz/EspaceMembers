<?php

namespace EspaceMembers\MainBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Cache\ApcCache;

class UserRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('last_name' => 'ASC'));
    }

    public function findTeachers()
    {
        return $qb = $this->createQueryBuilder('u')
            ->select('partial u.{
                id, first_name,
                last_name, address,
                phone, email, is_teacher
            }')
            ->addSelect('partial avatar.{
                id, providerName, providerStatus,
                providerReference, width, height,
                contentType, context
            }')

            ->innerJoin('u.teachings', 'tch', 'WITH', 'tch.is_show = 1')
            ->innerJoin('u.avatar','avatar')
            ->innerJoin('tch.event','ev')
            ->where('u.is_teacher = 1')
            ->orderBy('u.last_name', 'ASC')
            ->getQuery()
            ->useResultCache(true, 3600)
            ->getResult();
    }

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
            ->select('u')
            ->innerJoin('u.teachings', 't', 'WITH', 't.id = :teaching_id')
            ->where("u.id = :user_id")
            ->setParameter("teaching_id", $teachingId)
            ->setParameter("user_id", $userId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findUserBookmark($userId)
    {
        return $qb = $this->createQueryBuilder('u')
            ->select('partial u.{id}')
            ->addSelect('partial tu.{id, last_name, first_name}')
            ->addSelect('partial ev.{
                id, title, category,
                frontImage, startDate, completionDate
            }')
            ->addSelect('partial bk.{
                id, title, serial,
                lesson, dayNumber, dayTime, date
            }')
            ->addSelect('partial tch.{id}')
            ->addSelect('partial c.{id, year}')
            ->addSelect('partial lsn.{id, contentType, path}')
            ->addSelect('partial frntImg.{id, providerName, providerStatus, providerReference, width, height, contentType, context}')
            ->addSelect('partial avatar.{id, providerName, providerStatus, providerReference, width, height, contentType, context}')
            ->addSelect('partial tavatar.{id, providerName, providerStatus, providerReference, width, height, contentType, context}')

            ->innerJoin('u.bookmarks', 'bk')
            ->innerJoin('u.avatar','avatar')
            ->innerJoin('bk.event', 'ev')
            ->innerJoin('ev.users', 'tu')
            ->innerJoin('tu.teachings', 'tch', 'WITH', 'tch.id = bk.id')
            ->innerJoin('tu.avatar','tavatar')
            ->innerJoin('tch.lesson','lsn')
            ->innerJoin('ev.chronology', 'c')
            ->innerJoin('ev.frontImage','frntImg')
            ->where('u.id = :user_id')
            ->setParameter("user_id", $userId)
            ->orderBy('c.year', 'DESC')
            ->getQuery()
            ->getSingleResult();
    }
}
