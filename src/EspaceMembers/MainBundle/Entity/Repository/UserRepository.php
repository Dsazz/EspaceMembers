<?php

namespace EspaceMembers\MainBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

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
            ->where('u.is_teacher = 1')
            ->orderBy('u.last_name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findTeachingsByEvent($userId, $eventTitle)
    {
        return $qb = $this->createQueryBuilder('u')
            ->select('u')
            ->innerJoin('u.teachings', 't')
            ->innerJoin('t.event', 'e', 'WITH', 'e.title = :event_title')
            ->where('u.is_teacher = 1')
            ->andWhere('u.id = :user_id')
            ->setParameter("event_title", $eventTitle)
            ->setParameter("user_id", $userId)
            ->orderBy('u.last_name', 'ASC')
            ->getQuery()
            ->getSingleResult();
    }

    public function isBookmark($userId, $teachingId)
    {
        return (bool)$qb = $this->createQueryBuilder('u')
            ->select('u')
            ->innerJoin('u.teachings', 't', 'WITH', 't.id = :teaching_id')
            ->where("u.id = :user_id")
            ->setParameter("teaching_id", $teachingId)
            ->setParameter("user_id", $userId)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    public function findUserBookmark($userId)
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->addSelect('partial ev.{
                id, title, category, frontImage,
                startDate, completionDate, chronology
            }')
            ->addSelect('partial u.{id, last_name, first_name, avatar}')
            ->addSelect('partial bk.{
                id, title, serial,
                lesson, dayNumber, dayTime, date
            }')
            ->innerJoin('u.bookmarks', 'bk', 'WITH', 'u.id = :user_id')
            ->innerJoin('bk.event', 'ev')
            ->where('u.id = :user_id')
            ->setParameter('user_id', $userId)
            ->getQuery()
            ->getResult();
    }
}
