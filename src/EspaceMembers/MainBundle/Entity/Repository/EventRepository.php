<?php

namespace EspaceMembers\MainBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\ResultSetMapping;

use EspaceMembers\MainBundle\Entity\Event;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;


class EventRepository extends EntityRepository
{
    public function findEventWithTeachers($eventId)
    {
        return $qb = $this->createQueryBuilder('ev')
            ->select(
                'partial ev.{
                    id, title, category, year, frontImage,
                    startDate, completionDate, description
                }'
            )
            ->addSelect('partial c.{id, title}')
            ->addSelect('partial tgs.{id, title}')
            ->addSelect('partial u.{id, last_name, first_name, avatar}')
            ->addSelect(
                'partial tch.{
                    id, title, serial, lesson, dayNumber, dayTime, date
                }'
            )
            ->addSelect('partial lsn.{id, contentType, playtime}')
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
            ->innerJoin('ev.tags', 'tgs')
            ->innerJoin('u.teachings', 'tch', 'WITH', 'tch.is_show = 1 AND tch.event = ev')
            ->innerJoin('u.avatar', 'avatar')
            ->innerJoin('tch.lesson', 'lsn')
            ->where('ev.id = :event_id')
            ->setParameter('event_id', $eventId)
            ->getQuery()
            ->getOneOrNullResult();
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
            ->addSelect('partial lsn.{id, contentType, playtime}')
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

    public function filterByYearWithPaging($year)
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
            ->addSelect('partial lsn.{id, contentType, playtime}')
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
            ->innerJoin('u.teachings', 'tch', 'WITH', 'tch.is_show = 1 AND tch.event = ev')
            ->innerJoin('u.avatar', 'avatar')
            ->innerJoin('tch.lesson', 'lsn')
            ->where('ev.year = :year')
            ->orderBy('ev.year', 'DESC')

            ->setParameter('year', $year)
            ->getQuery()
            ->getArrayResult();

        $adapter = new ArrayAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);

        return $pagerfanta->setMaxPerPage(Event::MAX_PER_PAGE);
    }

    public function filterByCategoryWithPaging($categoryId)
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
            ->addSelect('partial lsn.{id, contentType, playtime}')
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
            ->innerJoin('ev.category', 'c', 'WITH', 'c.id = :category_id')
            ->innerJoin('ev.frontImage', 'frntImg')
            ->innerJoin('u.teachings', 'tch', 'WITH', 'tch.is_show = 1 AND tch.event = ev')
            ->innerJoin('u.avatar', 'avatar')
            ->innerJoin('tch.lesson', 'lsn')
            ->orderBy('ev.year', 'DESC')

            ->setParameter('category_id', $categoryId)
            ->getQuery()
            ->getArrayResult();

        $adapter = new ArrayAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);

        return $pagerfanta->setMaxPerPage(Event::MAX_PER_PAGE);
    }

    public function filterByTeacherWithPaging($teacherId)
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
            ->addSelect('partial lsn.{id, contentType, playtime}')
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
            ->innerJoin('ev.users', 'u', 'WITH', 'u.is_teacher = 1 AND u.id = :teacher_id')
            ->innerJoin('ev.frontImage', 'frntImg')
            ->innerJoin('ev.category', 'c')
            ->innerJoin('u.teachings', 'tch', 'WITH', 'tch.is_show = 1 AND tch.event = ev')
            ->innerJoin('u.avatar', 'avatar')
            ->innerJoin('tch.lesson', 'lsn')
            ->orderBy('ev.year', 'DESC')

            ->setParameter('teacher_id', $teacherId);

        $adapter = new ArrayAdapter($qb->getQuery()->getArrayResult());
        $pagerfanta = new Pagerfanta($adapter);

        return $pagerfanta->setMaxPerPage(Event::MAX_PER_PAGE);
    }

    public function filterByDirectionWithPaging($directionId)
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
            ->addSelect('partial lsn.{id, contentType, playtime}')
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
            ->innerJoin('u.teachings', 'tch', 'WITH', 'tch.is_show = 1 AND tch.event = ev')
            ->innerJoin('u.avatar', 'avatar')
            ->innerJoin('tch.lesson', 'lsn')
            ->innerJoin('tch.voies', 'v', 'WITH', 'v.id = :direction_id')
            ->orderBy('ev.year', 'DESC')

            ->setParameter('direction_id', $directionId)
            ->getQuery()
            ->getArrayResult();

        $adapter = new ArrayAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);

        return $pagerfanta->setMaxPerPage(Event::MAX_PER_PAGE);
    }

    public function filterByTagEvent($tagId)
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
            ->addSelect('partial lsn.{id, contentType, playtime}')
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
            ->innerJoin('u.teachings', 'tch', 'WITH', 'tch.is_show = 1 AND tch.event = ev')
            ->innerJoin('u.avatar', 'avatar')
            ->innerJoin('tch.lesson', 'lsn')
            ->orderBy('ev.year', 'DESC')

            ->setParameter('tag_id', $tagId)
            ->getQuery()
            ->getArrayResult();
    }

    public function filterByTagTeachingsAndEvents($tagId)
    {
        $rsm = new ResultSetMapping();

        $rsm->addEntityResult('EspaceMembers\MainBundle\Entity\Event', 'ev');
        $rsm->addFieldResult('ev', 'event_id', 'id');
        $rsm->addFieldResult('ev', 'title', 'title');
        $rsm->addFieldResult('ev', 'year', 'year');
        $rsm->addFieldResult('ev', 'startDate', 'startDate');
        $rsm->addFieldResult('ev', 'completionDate', 'completionDate');

        $rsm->addJoinedEntityResult('EspaceMembers\MainBundle\Entity\Category', 'c', 'ev', 'category');
        $rsm->addFieldResult('c', 'category_id', 'id');
        $rsm->addFieldResult('c', 'c_title', 'title');

        $rsm->addJoinedEntityResult('EspaceMembers\MainBundle\Entity\Media', 'frntImg', 'ev', 'frontImage');
        $rsm->addFieldResult('frntImg', 'front_image_id', 'id');
        $rsm->addFieldResult('frntImg', 'f_prov_name', 'providerName');
        $rsm->addFieldResult('frntImg', 'f_prov_status', 'providerStatus');
        $rsm->addFieldResult('frntImg', 'f_prov_ref', 'providerReference');
        $rsm->addFieldResult('frntImg', 'f_width', 'width');
        $rsm->addFieldResult('frntImg', 'f_height', 'height');
        $rsm->addFieldResult('frntImg', 'f_contentType', 'contentType');
        $rsm->addFieldResult('frntImg', 'f_context', 'context');

        $rsm->addJoinedEntityResult('EspaceMembers\MainBundle\Entity\User', 'u', 'ev', 'users');
        $rsm->addFieldResult('u', 'user_id', 'id');
        $rsm->addFieldResult('u', 'last_name', 'last_name');
        $rsm->addFieldResult('u', 'first_name', 'first_name');

        $rsm->addJoinedEntityResult('EspaceMembers\MainBundle\Entity\Media', 'avatar', 'u', 'avatar');
        $rsm->addFieldResult('avatar', 'avatar_id', 'id');
        $rsm->addFieldResult('avatar', 'provider_name', 'providerName');
        $rsm->addFieldResult('avatar', 'provider_status', 'providerStatus');
        $rsm->addFieldResult('avatar', 'provider_reference', 'providerReference');
        $rsm->addFieldResult('avatar', 'width', 'width');
        $rsm->addFieldResult('avatar', 'height', 'height');
        $rsm->addFieldResult('avatar', 'content_type', 'contentType');
        $rsm->addFieldResult('avatar', 'context', 'context');

        $rsm->addJoinedEntityResult('EspaceMembers\MainBundle\Entity\Teaching', 'tch', 'u', 'teachings');
        $rsm->addFieldResult('tch', 'teaching_id', 'id');
        $rsm->addFieldResult('tch', 'tch_title', 'title');
        $rsm->addFieldResult('tch', 'serial', 'serial');
        $rsm->addFieldResult('tch', 'dayNumber', 'dayNumber');
        $rsm->addFieldResult('tch', 'dayTime', 'dayTime');
        $rsm->addFieldResult('tch', 'date', 'date');

        $rsm->addJoinedEntityResult('EspaceMembers\MainBundle\Entity\Media', 'lsn', 'tch', 'lesson');
        $rsm->addFieldResult('lsn', 'lesson_id', 'id');
        $rsm->addFieldResult('lsn', 'lsn_contentType', 'contentType');
        $rsm->addFieldResult('lsn', 'playtime', 'playtime');

        $sql =
            '( SELECT ev.id as event_id, ev.title, ev.year, ev.startDate , ev.completionDate , '.
                'c.id as category_id, c.title as c_title, '.
                'frntImg.id as front_image_id, frntImg.provider_name as f_prov_name, frntImg.provider_status as f_prov_status, '.
                'frntImg.provider_reference as f_prov_ref, frntImg.width as f_width, frntImg.height as f_height, '.
                'frntImg.content_type as f_contentType, frntImg.context as f_context, '.
                'u.id as user_id, u.last_name, u.first_name, '.
                'avatar.id as avatar_id, avatar.provider_name, avatar.provider_status, '.
                'avatar.provider_reference, avatar.width, avatar.height, avatar.content_type, avatar.context, '.
                'tch.id as teaching_id, tch.title as tch_title, tch.serial, tch.dayNumber, tch.dayTime, tch.date, '.
                'lsn.id as lesson_id, lsn.content_type as lsn_contentType, lsn.playtime '.

            'FROM event as ev '.

            'JOIN category as c ON c.id = ev.category_id '.
            'JOIN media__media as frntImg ON frntImg.id = ev.front_image_id '.
            'JOIN users_events as ue ON ue.event_id = ev.id '.
            'JOIN fos_user as u ON u.id = ue.user_id '.
            'JOIN media__media as avatar ON avatar.id = u.avatar_id '.
            'JOIN teachings_users as tu ON tu.user_id = u.id '.
            'JOIN teaching as tch ON tch.id = tu.teaching_id AND (tch.is_show = 1 AND tch.event_id = ev.id) '.
            'JOIN media__media as lsn ON lsn.id = tch.media_id '.
            'JOIN tags_teachings as tt ON tt.tag_id = :tag_id AND tt.teaching_id = tch.id '.

            'ORDER BY ev.year DESC )'.

            'UNION '.

            '( SELECT ev.id as event_id, ev.title, ev.year, ev.startDate , ev.completionDate , '.
                'c.id as category_id, c.title as c_title, '.
                'frntImg.id as front_image_id, frntImg.provider_name as f_prov_name, frntImg.provider_status as f_prov_status, '.
                'frntImg.provider_reference as f_prov_ref, frntImg.width as f_width, frntImg.height as f_height, '.
                'frntImg.content_type as f_contentType, frntImg.context as f_context, '.
                'u.id as user_id, u.last_name, u.first_name, '.
                'avatar.id as avatar_id, avatar.provider_name, avatar.provider_status, '.
                'avatar.provider_reference, avatar.width, avatar.height, avatar.content_type, avatar.context, '.
                'tch.id as teaching_id, tch.title as tch_title, tch.serial, tch.dayNumber, tch.dayTime, tch.date, '.
                'lsn.id as lesson_id, lsn.content_type as lsn_contentType, lsn.playtime '.

            'FROM event as ev '.

            'JOIN tags_events as te ON te.tag_id = :tag_id AND te.event_id = ev.id '.
            'JOIN category as c ON c.id = ev.category_id '.
            'JOIN media__media as frntImg ON frntImg.id = ev.front_image_id '.
            'JOIN users_events as ue ON ue.event_id = ev.id '.
            'JOIN fos_user as u ON u.id = ue.user_id '.
            'JOIN media__media as avatar ON avatar.id = u.avatar_id '.
            'JOIN teachings_users as tu ON tu.user_id = u.id '.
            'JOIN teaching as tch ON tch.id = tu.teaching_id AND (tch.is_show = 1 AND tch.event_id = ev.id) '.
            'JOIN media__media as lsn ON lsn.id = tch.media_id '.

            'ORDER BY ev.year DESC )'
        ;

        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter('tag_id', $tagId);

        $adapter = new ArrayAdapter($query->getArrayResult());
        $pagerfanta = new Pagerfanta($adapter);

        return $pagerfanta->setMaxPerPage(Event::MAX_PER_PAGE);
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
            ->addSelect('partial lsn.{id, contentType, playtime}')
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
