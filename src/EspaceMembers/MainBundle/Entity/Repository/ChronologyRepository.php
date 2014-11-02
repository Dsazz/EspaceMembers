<?php

namespace EspaceMembers\MainBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Common\Cache\ApcCache;

class ChronologyRepository extends EntityRepository
{
    public function findAllWithBookmarks($userId)
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->addSelect('partial ev.{id, title, category, frontImage, startDate, completionDate }')
            ->addSelect('partial u.{id, last_name, first_name, avatar}')
            ->addSelect('partial tch.{id, title, serial, lesson, dayNumber, dayTime, date}')
            ->addSelect('partial bk.{id}')
            ->innerJoin('c.events','ev')
            ->innerJoin('ev.users','u', 'WITH', 'u.is_teacher = 1')
            ->innerJoin('u.teachings','tch', 'WITH', 'tch.is_show = 1')
            ->leftJoin('u.bookmarks','bk', 'WITH', 'u.id = :user_id')
            ->orderBy('c.year', 'DESC')
            ->setParameter('user_id', $userId)
            ->getQuery()
            ->getResult();
    }

    public function filterById($chronologyId, $userId)
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->addSelect('partial ev.{
                id, title, category,
                frontImage, startDate, completionDate
            }')
            ->addSelect('partial u.{id, last_name, first_name, avatar}')
            ->addSelect('partial tch.{
                id, title, serial,
                lesson, dayNumber, dayTime, date
            }')
            ->addSelect('partial bk.{id}')
            ->innerJoin('c.events','ev')
            ->innerJoin('ev.users','u', 'WITH', 'u.is_teacher = 1')
            ->innerJoin('u.teachings','tch', 'WITH', 'tch.is_show = 1')
            ->leftJoin('u.bookmarks','bk', 'WITH', 'u.id = :user_id')
            ->where('c.id = :chronology_id')
            ->orderBy('c.year', 'DESC')
            ->setParameter('chronology_id', $chronologyId)
            ->setParameter('user_id', $userId)
            ->getQuery()
            ->getResult();
    }

    public function filterByCategory($categoryId, $userId)
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->addSelect('partial ev.{
                id, title, category,
                frontImage, startDate, completionDate
            }')
            ->addSelect('partial u.{id, last_name, first_name, avatar}')
            ->addSelect('partial tch.{
                id, title, serial,
                lesson, dayNumber, dayTime, date
            }')
            ->addSelect('partial bk.{id}')
            ->innerJoin('c.events','ev')
            ->innerJoin('ev.users','u', 'WITH', 'u.is_teacher = 1')
            ->innerJoin('ev.category','ct', 'WITH', 'ct.id = :category_id')
            ->innerJoin('u.teachings','tch', 'WITH', 'tch.is_show = 1')
            ->leftJoin('u.bookmarks','bk', 'WITH', 'u.id = :user_id')
            ->orderBy('c.year', 'DESC')
            ->setParameter('category_id', $categoryId)
            ->setParameter('user_id', $userId)
            ->getQuery()
            ->getResult();
    }

    public function filterByTeacher($userId)
    {
        $qb = $this->createQueryBuilder('c');

        return $qb
            ->select('c')
            ->addSelect('partial ev.{
                id, title, category,
                frontImage, startDate, completionDate
            }')
            ->addSelect('partial u.{id, last_name, first_name, avatar}')
            ->addSelect('partial tch.{
                id, title, serial,
                lesson, dayNumber, dayTime, date
            }')
            ->addSelect('partial bk.{id}')
            ->innerJoin('c.events','ev')
            ->innerJoin('ev.users','u', Join::WITH,
                $qb->expr()->andX(
                    $qb->expr()->eq('u.id', ':user_id'),
                    $qb->expr()->eq('u.is_teacher', '1')
                ))
            ->innerJoin('u.teachings','tch', 'WITH', 'tch.is_show = 1')
            ->leftJoin('u.bookmarks','bk', 'WITH', 'u.id = :user_id')
            ->setParameter('user_id', $userId)
            ->getQuery()
            ->getResult();
    }

    public function filterByVoie($voieId, $userId)
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->addSelect('partial ev.{
                id, title, category,
                frontImage, startDate, completionDate
            }')
            ->addSelect('partial u.{id, last_name, first_name, avatar}')
            ->addSelect('partial tch.{
                id, title, serial,
                lesson, dayNumber, dayTime, date
            }')
            ->addSelect('partial bk.{id}')
            ->innerJoin('c.events','ev')
            ->innerJoin('ev.users','u', 'WITH', 'u.is_teacher = 1')
            ->innerJoin('u.teachings','tch', 'WITH', 'tch.is_show = 1')
            ->leftJoin('u.bookmarks','bk', 'WITH', 'u.id = :user_id')
            ->innerJoin('tch.voies', 'v', 'WITH', 'v.id = :voie_id')
            ->setParameter('voie_id', $voieId)
            ->setParameter('user_id', $userId)
            ->getQuery()
            ->getResult();
    }

    public function filterByTagEvent($tagId, $userId)
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->addSelect('partial ev.{
                id, title, category,
                frontImage, startDate, completionDate
            }')
            ->addSelect('partial u.{id, last_name, first_name, avatar}')
            ->addSelect('partial tch.{
                id, title, serial,
                lesson, dayNumber, dayTime, date
            }')
            ->addSelect('partial bk.{id}')
            ->innerJoin('c.events','ev')
            ->innerJoin('ev.users','u', 'WITH', 'u.is_teacher = 1')
            ->innerJoin('ev.tags','etg', 'WITH', 'etg.id = :tag_id')
            ->innerJoin('u.teachings','tch', 'WITH', 'tch.is_show = 1')
            ->leftJoin('u.bookmarks','bk', 'WITH', 'u.id = :user_id')
            ->setParameter('tag_id', $tagId)
            ->setParameter('user_id', $userId)
            ->getQuery()
            ->getResult();
    }

    public function filterByTagTeaching($tagId, $userId)
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->addSelect('partial ev.{
                id, title, category,
                frontImage, startDate, completionDate
            }')
            ->addSelect('partial u.{id, last_name, first_name, avatar}')
            ->addSelect('partial tch.{
                id, title, serial,
                lesson, dayNumber, dayTime, date
            }')
            ->addSelect('partial bk.{id}')
            ->innerJoin('c.events','ev')
            ->innerJoin('ev.users','u', 'WITH', 'u.is_teacher = 1')
            ->innerJoin('u.teachings','tch', 'WITH', 'tch.is_show = 1')
            ->leftJoin('u.bookmarks','bk', 'WITH', 'u.id = :user_id')
            ->innerJoin('tch.tags', 'etch', 'WITH', 'etch.id = :tag_id')
            ->setParameter('tag_id', $tagId)
            ->setParameter('user_id', $userId)
            ->getQuery()
            ->getResult();
    }

    public function getYears()
    {
        $cacheDriver = new ApcCache();

        if ($cacheDriver->contains('_years')) {
            return $cacheDriver->fetch('_years');
        }

        $qb = $this->createQueryBuilder('c')
            ->select('partial c.{id, year}')
            ->orderBy('c.year', 'DESC');

        $years = $qb->getQuery()->getResult();

        $cacheDriver->save('_years', $years, 60);

        return $years;
    }
}
