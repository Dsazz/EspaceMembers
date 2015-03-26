<?php

namespace EspaceMembers\MainBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use EspaceMembers\MainBundle\Entity\Event;

class EventRepository extends EntityRepository
{
    public function findEventWithTeachers($eventId)
    {
        return $qb = $this->createQueryBuilder('ev')
            ->select('partial ev.{id, title, year, frontImage, category}')
            ->addSelect('partial u.{id, avatar, last_name, first_name}')
            ->innerJoin('ev.users', 'u')
            ->innerJoin('u.teachings', 't', 't.is_show = 1')
            ->innerJoin('t.event', 'e2', 'WITH', 'e2.title = ev.title')
            ->where('ev.id = :event_id')
            ->setParameter('event_id', $eventId)
            ->getQuery()
            ->getSingleResult();
    }

    public function findAllWithPaging()
    {
        $qb = $this->createQueryBuilder('ev')
            ->select(
                'partial ev.{
                    id, title, category, year, frontImage, startDate, completionDate
                }'
            )
            ->addSelect('partial c.{id, title}')
            ->addSelect('partial u.{id, last_name, first_name, avatar}')
            ->addSelect(
                'partial tch.{
                    id, title, serial, lesson, dayNumber, dayTime, date
                }'
            )
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
            ->innerJoin('ev.users', 'u', 'WITH', 'u.is_teacher = 1')
            ->innerJoin('ev.category', 'c')
            ->innerJoin('ev.frontImage', 'frntImg')
            ->innerJoin('u.teachings', 'tch', 'WITH', 'tch.is_show = 1 AND tch.event = ev')
            ->innerJoin('u.avatar', 'avatar')
            ->innerJoin('tch.lesson', 'lsn')
            ->where('tch.event = ev')
            ->orderBy('ev.year', 'DESC')
            ->getQuery()
            ->getArrayResult();

        $adapter = new ArrayAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);

        return $pagerfanta->setMaxPerPage(Event::MAX_PER_PAGE);
    }

    public function filterByYearWithPaging($year, $userId)
    {
        $qb = $this->createQueryBuilder('ev')
            ->select(
                'partial ev.{
                    id, title, category, year, frontImage, startDate, completionDate
                }'
            )
            ->addSelect('partial u.{id, last_name, first_name, avatar}')
            ->addSelect(
                'partial tch.{
                    id, title, serial, lesson, dayNumber, dayTime, date
                }'
            )
            ->addSelect('partial c.{id, title}')
            ->addSelect('partial bk.{id}')
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
            ->innerJoin('ev.users', 'u', 'WITH', 'u.is_teacher = 1')
            ->innerJoin('ev.frontImage', 'frntImg')
            ->innerJoin('ev.category', 'c')
            ->innerJoin('u.teachings', 'tch', 'WITH', 'tch.is_show = 1')
            ->innerJoin('u.avatar', 'avatar')
            ->innerJoin('tch.lesson', 'lsn')
            ->leftJoin('u.bookmarks', 'bk', 'WITH', 'u.id = :user_id')
            ->where('ev.year = :year')
            ->orderBy('ev.year', 'DESC')
            ->setParameter('year', $year)
            ->setParameter('user_id', $userId)
            ->getQuery()
            ->getArrayResult();

        $adapter = new ArrayAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);

        return $pagerfanta->setMaxPerPage(Event::MAX_PER_PAGE);
    }

    public function filterByCategoryWithPaging($categoryId, $userId)
    {
        $qb = $this->createQueryBuilder('ev')
            ->select(
                'partial ev.{
                    id, title, category, year, frontImage, startDate, completionDate
                }'
            )
            ->addSelect('partial u.{id, last_name, first_name, avatar}')
            ->addSelect(
                'partial tch.{
                    id, title, serial, lesson, dayNumber, dayTime, date
                }'
            )
            ->addSelect('partial c.{id, title}')
            ->addSelect('partial bk.{id}')
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
            ->innerJoin('ev.users', 'u', 'WITH', 'u.is_teacher = 1')
            ->innerJoin('ev.category', 'ct', 'WITH', 'ct.id = :category_id')
            ->innerJoin('ev.frontImage', 'frntImg')
            ->innerJoin('ev.category', 'c')
            ->innerJoin('u.teachings', 'tch', 'WITH', 'tch.is_show = 1')
            ->innerJoin('u.avatar', 'avatar')
            ->innerJoin('tch.lesson', 'lsn')
            ->leftJoin('u.bookmarks', 'bk', 'WITH', 'u.id = :user_id')
            ->orderBy('ev.year', 'DESC')
            ->setParameter('category_id', $categoryId)
            ->setParameter('user_id', $userId)
            ->getQuery()
            ->getArrayResult();

        $adapter = new ArrayAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);

        return $pagerfanta->setMaxPerPage(Event::MAX_PER_PAGE);
    }

    public function filterByTeacherWithPaging($userId)
    {
        $qb = $this->createQueryBuilder('ev');

        $qb
            ->select(
                'partial ev.{
                    id, title, category, year, frontImage, startDate, completionDate
                }'
            )
            ->addSelect('partial u.{id, last_name, first_name, avatar}')
            ->addSelect('partial tch.{
                id, title, serial, lesson, dayNumber, dayTime, date
                }'
            )
            ->addSelect('partial c.{id, title}')
            ->addSelect('partial bk.{id}')
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
            ->innerJoin(
                'ev.users', 'u', Join::WITH, $qb->expr()->andX(
                    $qb->expr()->eq('u.id', ':user_id'),
                    $qb->expr()->eq('u.is_teacher', '1')
                )
            )
            ->innerJoin('ev.frontImage', 'frntImg')
            ->innerJoin('ev.category', 'c')
            ->innerJoin('u.teachings', 'tch', 'WITH', 'tch.is_show = 1')
            ->leftJoin('u.bookmarks', 'bk', 'WITH', 'u.id = :user_id')
            ->innerJoin('u.avatar', 'avatar')
            ->innerJoin('tch.lesson', 'lsn')
            ->setParameter('user_id', $userId);

        $adapter = new ArrayAdapter($qb->getQuery()->getArrayResult());
        $pagerfanta = new Pagerfanta($adapter);

        return $pagerfanta->setMaxPerPage(Event::MAX_PER_PAGE);
    }

    public function filterByDirectionWithPaging($directionId, $userId)
    {
        $qb = $this->createQueryBuilder('ev')
            ->select(
                'partial ev.{
                    id, title, category, year, frontImage, startDate, completionDate
                }'
            )
            ->addSelect('partial u.{id, last_name, first_name, avatar}')
            ->addSelect(
                'partial tch.{
                    id, title, serial, lesson, dayNumber, dayTime, date
                }'
            )
            ->addSelect('partial c.{id, title}')
            ->addSelect('partial bk.{id}')
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
            ->innerJoin('ev.users', 'u', 'WITH', 'u.is_teacher = 1')
            ->innerJoin('ev.frontImage', 'frntImg')
            ->innerJoin('ev.category', 'c')
            ->innerJoin('u.teachings', 'tch', 'WITH', 'tch.is_show = 1')
            ->leftJoin('u.bookmarks', 'bk', 'WITH', 'u.id = :user_id')
            ->innerJoin('u.avatar', 'avatar')
            ->innerJoin('tch.lesson', 'lsn')
            ->innerJoin('tch.voies', 'v', 'WITH', 'v.id = :direction_id')
            ->setParameter('direction_id', $directionId)
            ->setParameter('user_id', $userId)
            ->getQuery()
            ->getArrayResult();

        $adapter = new ArrayAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);

        return $pagerfanta->setMaxPerPage(Event::MAX_PER_PAGE);
    }

    public function filterByTagEvent($tagId, $userId)
    {
        return $this->createQueryBuilder('ev')
            ->select(
                'partial ev.{
                    id, title, category, year, frontImage, startDate, completionDate
                }'
            )
            ->addSelect('partial u.{id, last_name, first_name, avatar}')
            ->addSelect(
                'partial tch.{id, title, serial, lesson, dayNumber, dayTime, date
                }'
            )
            ->addSelect('partial c.{id, title}')
            ->addSelect('partial bk.{id}')
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
            ->innerJoin('ev.users', 'u', 'WITH', 'u.is_teacher = 1')
            ->innerJoin('ev.tags', 'etg', 'WITH', 'etg.id = :tag_id')
            ->innerJoin('ev.frontImage', 'frntImg')
            ->innerJoin('ev.category', 'c')
            ->innerJoin('u.teachings', 'tch', 'WITH', 'tch.is_show = 1')
            ->innerJoin('u.avatar', 'avatar')
            ->leftJoin('u.bookmarks', 'bk', 'WITH', 'u.id = :user_id')
            ->innerJoin('tch.lesson', 'lsn')

            ->setParameter('tag_id', $tagId)
            ->setParameter('user_id', $userId)
            ->getQuery()
            ->getArrayResult();
    }

    public function filterByTagTeaching($tagId, $userId)
    {
        return $this->createQueryBuilder('ev')
            ->select(
                'partial ev.{
                    id, title, category, year, frontImage, startDate, completionDate
                }'
            )
            ->addSelect('partial u.{id, last_name, first_name, avatar}')
            ->addSelect(
                'partial tch.{id, title, serial, lesson, dayNumber, dayTime, date
                }'
            )
            ->addSelect('partial c.{id, title}')
            ->addSelect('partial bk.{id}')
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
            ->innerJoin('ev.users', 'u', 'WITH', 'u.is_teacher = 1')
            ->innerJoin('ev.frontImage', 'frntImg')
            ->innerJoin('ev.category', 'c')
            ->innerJoin('u.teachings', 'tch', 'WITH', 'tch.is_show = 1')
            ->innerJoin('u.avatar', 'avatar')
            ->leftJoin('u.bookmarks', 'bk', 'WITH', 'u.id = :user_id')
            ->innerJoin('tch.lesson', 'lsn')
            ->innerJoin('tch.tags', 'etch', 'WITH', 'etch.id = :tag_id')

            ->setParameter('tag_id', $tagId)
            ->setParameter('user_id', $userId)
            ->getQuery()
            ->getArrayResult();
    }

    public function getYears()
    {
        return $qb = $this->createQueryBuilder('ev')
            ->select('partial ev.{id, year}')
            ->groupBy('ev.year')
            ->getQuery()
            ->useResultCache(true, 3600)
            ->getResult();
    }




    public function findBookmarks($bookmarkData = array())
    {
        $qb = $this->createQueryBuilder('ev')
            ->select(
                'partial ev.{
                    id, title, category, year, frontImage, startDate, completionDate
                }'
            )
            ->addSelect('partial c.{id, title}')
            ->addSelect('partial u.{id, last_name, first_name, avatar}')
            ->addSelect(
                'partial tch.{
                    id, title, serial, lesson, dayNumber, dayTime, date
                }'
            )
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
            ->innerJoin('ev.users', 'u', 'WITH', 'u.is_teacher = 1 AND u.id IN (:teachers) ')
            ->innerJoin('ev.category', 'c')
            ->innerJoin('ev.frontImage', 'frntImg')
            ->innerJoin('u.teachings', 'tch', 'WITH', 'tch.is_show = 1 AND tch.event IN (:events) AND tch.id IN (:teachings)')
            ->innerJoin('u.avatar', 'avatar')
            ->innerJoin('tch.lesson', 'lsn')
            ->where('ev.id IN (:events)')
            ->orderBy('ev.year', 'DESC')

            ->setParameter('teachers', $bookmarkData['user_id'])
            ->setParameter('teachings', $bookmarkData['teaching_id'])
            ->setParameter('events', $bookmarkData['event_id'])
            ->getQuery()
            ->getArrayResult();

        $adapter = new ArrayAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);

        return $pagerfanta->setMaxPerPage(Event::MAX_PER_PAGE);
    }
}
