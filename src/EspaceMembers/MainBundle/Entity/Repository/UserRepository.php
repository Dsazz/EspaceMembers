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
        $cacheDriver = new ApcCache();

        if ($cacheDriver->contains('_teachers')) {
            return $cacheDriver->fetch('_teachers');
        }

        $qb = $this->createQueryBuilder('u')
            ->select('partial u.{
                id, first_name,
                last_name, address,
                phone, email,
                avatar,  is_teacher
            }')
            ->where('u.is_teacher = 1')
            ->orderBy('u.last_name', 'ASC');

        $teachers = $qb->getQuery()->getResult();

        $cacheDriver->save('_teachers', $teachers, 60);

        return $teachers;
    }

    public function findTeachingsByEvent($userId, $eventTitle)
    {
        return $qb = $this->createQueryBuilder('u')
            ->select('u, t')
            ->innerJoin('u.teachings', 't', 'WITH', 't.is_show = 1')
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
        return (bool) $qb = $this->createQueryBuilder('u')
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
    }
}
