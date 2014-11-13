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
                phone, email,
                avatar,  is_teacher
            }')

            ->innerJoin('u.teachings', 'tch', 'WITH', 'tch.is_show = 1')
            ->innerJoin('tch.event','ev')
            ->where('u.is_teacher = 1')
            ->groupBy('u.id')
            ->having('COUNT(tch.id) > 0 AND COUNT(ev.id) > 0')
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
            //->getSingleResult();
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
            //->getSingleResult();
            ->getOneOrNullResult();
    }

    public function findUserBookmark($userId)
    {
        return $qb = $this->createQueryBuilder('u')
            ->select('partial u.{id}')
            ->addSelect('partial tu.{id, last_name, first_name, avatar}')
            ->addSelect('partial ev.{
                id, title, category,
                frontImage, startDate, completionDate
            }')
            ->addSelect('partial bk.{
                id, title, serial,
                lesson, dayNumber, dayTime, date
            }')
            ->addSelect('partial tch2.{id}')
            ->addSelect('partial c.{id, year}')
            ->innerJoin('u.bookmarks', 'bk')
            ->innerJoin('bk.event', 'ev')
            ->innerJoin('ev.users', 'tu')
            ->innerJoin('tu.teachings', 'tch2', 'WITH', 'tch2.id = bk.id')
            ->innerJoin('ev.chronology', 'c')
            ->where('u.id = :user_id')
            ->setParameter("user_id", $userId)
            ->orderBy('c.year', 'DESC')
            ->getQuery()
            ->getSingleResult();
            //->getResult();
    }
}
