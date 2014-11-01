<?php

namespace EspaceMembers\MainBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{
    public function findEventWithTeachers($eventId)
    {
        return $qb = $this->createQueryBuilder('e')
            ->select('partial e.{id, title, frontImage, category}')
            ->addSelect('partial u.{id, avatar, last_name, first_name}')
            ->innerJoin('e.users', 'u')
            ->innerJoin('u.teachings', 't')
            ->innerJoin('t.event', 'e2', 'WITH', 'e2.title = e.title')
            ->where('e.id = :event_id')
            ->setParameter('event_id', $eventId)
            ->getQuery()
            ->getSingleResult();
    }
}
